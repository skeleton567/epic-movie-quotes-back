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
            'quote_ka' => 'required|regex:/^[აბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ.,!?\s]*$/',
            'quote_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'movie_id' => 'required',
            'image' => 'image'
        ];
    }
    public function messages()
    {
        return [
             'image.image' => [
                'ka' => __('validation.image', ['attribute' => 'სურათი'], 'ka'),
                'en' => __('validation.image', ['attribute' => 'Image'], 'en'),
             ],
        ];
    }
}
