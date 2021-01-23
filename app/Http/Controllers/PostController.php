<?php

namespace App\Http\Controllers;

use App\Models\{Post, Category};
use Illuminate\Http\Request;
use App\Http\Requests\{PostRequest, PostPublishRequest};
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
        $perPage = is_numeric($request->get('per-page')) ? $request->get('per-page') : null;

        $query = $isAdmin ? Post::query() : Post::whereNotNull('published_at');

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        return PostResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostRequest  $request
     * @return PostResource
     */
    public function store(PostRequest $request): PostResource
    {
        $params = $request->only([
            'title',
            'text',
            'slug',
            'category_id',
        ]);

        $post = new Post($params);
        $post->togglePublish($request->get('publish'));
        $post->save();

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
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest $request
     * @param  Post $post
     * @return PostResource
     */
    public function update(PostRequest $request, Post $post): PostResource
    {
        $params = $request->only([
            'title',
            'text',
            'slug',
            'category_id',
        ]);

        $post->update($params);
        $post->togglePublish($request->get('publish'));

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
}
