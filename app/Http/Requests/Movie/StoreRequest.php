<?php

namespace App\Http\Requests\Movie;

use Doctrine\Common\Annotations\Annotation\Required;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title_en' => 'required',
            'title_ka' => 'required',
            'director_en' => 'required',
            'director_ka' => 'required',
            'release_year' => 'required',
            'budget' => 'required',
            'description_ka' => 'required',
            'description_en' => 'required',
            'image' => 'required|image'
        ];
    }
}
