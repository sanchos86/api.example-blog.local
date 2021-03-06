<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category, Tag};
use Illuminate\Http\Request;
use App\Http\Requests\Post\{PostCreateRequest, PostPublishRequest, PostUpdateRequest};
use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'index',
            'show',
            'getLatest'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $isAdmin = optional(auth()->user())->isAdmin();
        $categorySlug = $request->query('category');
        $tagSlug = $request->query('tag');
        $perPage = is_numeric($request->query('perPage')) ? $request->query('perPage') : null;

        $query = $isAdmin ? Post::query() : Post::whereNotNull('published_at');

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        } else if ($tagSlug) {
            $tag = Tag::where('slug', $tagSlug)->first();
            if ($tag) {
                $query->whereHas('tags', function ($query) use ($tag) {
                    $query->where('tag_id', $tag->id);
                });
            }
        }

        return PostResource::collection($query->latest()->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostCreateRequest  $request
     * @return PostResource
     */
    public function store(PostCreateRequest $request): PostResource
    {
        $params = $request->only([
            'title',
            'text',
            'slug',
            'category_id',
            'plain_text'
        ]);
        $src = $request->file('picture')->store(null);
        $params['src'] = $src;

        $post = new Post($params);
        $post->togglePublish($request->get('publish'));
        $post->save();
        $post->tags()->attach($request->get('tags'));

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return PostResource
     */
    public function show(string $slug): PostResource
    {
        $isAdmin = optional(auth()->user())->isAdmin();
        $query = Post::where('slug', $slug);
        if (!$isAdmin) {
            $query->whereNotNull('published_at');
        }
        $post = $query->firstOrFail();
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest $request
     * @param  Post $post
     * @return PostResource
     */
    public function update(PostUpdateRequest $request, Post $post): PostResource
    {
        $params = $request->only([
            'title',
            'text',
            'slug',
            'category_id',
            'plain_text'
        ]);

        if ($request->hasFile('picture')) {
            $src = $request->file('picture')->store(null);
            $params['src'] = $src;
        }

        $post->togglePublish($request->get('publish'));
        $post->update($params);
        $post->tags()->sync($request->get('tags'));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     * @return JsonResponse
     * @throws
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json([], 204);
    }

    /**
     * @param PostPublishRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function publish(PostPublishRequest $request, Post $post): PostResource
    {
        $post->togglePublish($request->get('publish'));
        return new PostResource($post);
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getLatest(): AnonymousResourceCollection
    {
        $posts = Post::whereNotNull('published_at')->orderByDesc('id')->take(5)->get();
        return PostResource::collection($posts);
    }
}
