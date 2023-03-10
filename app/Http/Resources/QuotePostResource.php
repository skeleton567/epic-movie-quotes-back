<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotePostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'quote' => $this->getTranslations('quote'),
            'quote_ka' =>$this->getTranslation('quote', 'ka'),
            'quote_en' =>$this->getTranslation('quote', 'en'),
            'movie' => MovieResource::make($this->movie)->getTranslations('title'),
            'year' => MovieResource::make($this->movie)->release_year,
            'movie_id' => MovieResource::make($this->movie)->id,
            'image' => $this->image,
            'user' => UserResource::make($this->user),
            'like' => LikeResource::collection($this->likes),
            'comment' => CommentResource::collection($this->comments)
        ];
    }
}
