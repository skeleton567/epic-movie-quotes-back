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
            'title_ka' =>$this->getTranslation('title', 'ka'),
            'title_en' =>$this->getTranslation('title', 'en'),
            'director' => $this->director,
            'director_en' =>$this->getTranslation('director', 'ka'),
            'director_ka' =>$this->getTranslation('director', 'en'),
            'description' => $this->description,
            'description_ka' =>$this->getTranslation('description', 'ka'),
            'description_en' =>$this->getTranslation('description', 'en'),
            'year' => $this->release_year,
            'budget' => $this->budget,
            'image' => $this->image,
            'categories' => CategoryResource::collection($this->categories),
            'quote' => QuoteMovieResource::collection($this->quotes)
        ];
    }
}
