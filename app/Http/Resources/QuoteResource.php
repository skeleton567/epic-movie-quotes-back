<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
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
            'quote' => $this->quote,
            'movie' => MovieResource::make($this->movie),
            'user' => UserResource::make($this->user),
            'like' => LikeResource::collection($this->likes),
            'comment' => CommentResource::collection($this->comments)
        ];
    }
}
