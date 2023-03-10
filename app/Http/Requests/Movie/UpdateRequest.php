<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'title_ka' => 'required|regex:/^[ა-ჰ.,!?\s]*$/',
            'director_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'director_ka' => 'required|regex:/^[ა-ჰ.,!?\s]*$/',
            'release_year' => 'required',
            'budget' => 'required',
            'description_ka' => 'required|regex:/^[ა-ჰ.,!?\s]*$/',
            'description_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ];
    }
}
