<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{Category, PostView};

/**
 * Class PostResource
 * @property integer id
 * @property string title
 * @property string text
 * @property string slug
 * @property Category category
 * @property DateTime|null published_at
 * @property Collection tags
 * @property PostView postView
 *
 * @package App\Http\Resources
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'publishedAt' => $this->published_at,
            'tags' => TagResource::collection($this->tags),
            'postViews' => $this->postView->counter,
        ];
    }
}
