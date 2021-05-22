<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\{Category};
use Illuminate\Support\Facades\Storage;

/**
 * Class PostResource
 * @property integer id
 * @property string title
 * @property string text
 * @property string slug
 * @property Category category
 * @property DateTime|null published_at
 * @property Collection tags
 * @property string src
 * @property string plain_text
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
        $src = Storage::exists($this->src) ? Storage::url($this->src) : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'publishedAt' => $this->published_at,
            'tags' => TagResource::collection($this->tags),
            'src' => $src,
            'plainText' => $this->plain_text
        ];
    }
}
