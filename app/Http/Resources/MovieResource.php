<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'title' => $this->title,
            'director' => $this->director,
            'description' => $this->description,
            'year' => $this->release_year,
            'budget' => $this->budget,
            'image' => $this->image,
            'categories' => CategoryResource::collection($this->categories),
            'quote' => QuoteMovieResource::collection($this->quotes)
        ];
    }
}
