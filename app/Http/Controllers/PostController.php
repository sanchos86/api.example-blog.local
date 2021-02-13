<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category, Tag, PostView};
use Illuminate\Http\Request;
use App\Http\Requests\{PostCreateRequest, PostPublishRequest, PostUpdateRequest};
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
            'getPopular'
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
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $categorySlug = $request->get('category');
        $tagSlug = $request->get('tag');
        $perPage = is_numeric($request->get('perPage')) ? $request->get('perPage') : null;

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

        return PostResource::collection($query->paginate($perPage));
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
        ]);
        $src = $request->file('picture')->store(null);
        $params['src'] = $src;

        $post = new Post($params);
        $postView = new PostView();
        $post->togglePublish($request->get('publish'));
        $post->save();
        $post->tags()->attach($request->get('tags'));
        $post->postView()->save($postView);

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
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $query = Post::where('slug', $slug);
        if (!$isAdmin) {
            $query->whereNotNull('published_at');
        }
        $post = $query->firstOrFail();
        if (!$isAdmin) {
            $counter = $post->postView->counter + 1;
            $post->postView->update([
                'counter' => $counter
            ]);
        }
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
        ]);

        if ($request->file('picture')) {
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
    public function getPopular(): AnonymousResourceCollection
    {
        $posts = Post::join('post_views', 'posts.id', '=', 'post_views.post_id')->orderBy('post_views.counter', 'desc')->take(5)->get();
        return PostResource::collection($posts);
    }
}
