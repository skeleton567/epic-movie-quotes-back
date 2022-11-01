<?php

namespace App\Http\Requests\Quote;

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
            'quote_ka' => 'required',
            'quote_en' => 'required',
            'movie_id' => 'required',
            'image' => 'required|image'
        ];
    }
}
