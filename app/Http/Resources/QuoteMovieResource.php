<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteMovieResource extends JsonResource
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
        'quote' =>$this->getTranslations('quote'),
        'image' => $this->image,
        'like' => LikeResource::collection($this->likes),
        'user_id' => $this->user_id,
        'comment' => CommentResource::collection($this->comments),
    ];
    }
}
