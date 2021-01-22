<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Tag;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $tags = Tag::all();
        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TagRequest  $request
     * @return TagResource
     */
    public function store(TagRequest $request): TagResource
    {
        $params = $request->only([
            'name',
            'slug',
        ]);
        $tag = Tag::create($params);
        return new TagResource($tag);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tag $tag
     * @return TagResource
     */
    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TagRequest $request
     * @param  Tag $tag
     * @return TagResource
     */
    public function update(TagRequest $request, Tag $tag): TagResource
    {
        $params = $request->only([
            'name',
            'slug',
        ]);
        $tag->update($params);
        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tag $tag
     * @return JsonResponse
     * @throws
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();
        return response()->json([], 204);
    }
}
