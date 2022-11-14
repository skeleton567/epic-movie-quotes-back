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
            'title_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'title_ka' => 'required|regex:/^[აბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ.,!?\s]*$/',
            'director_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'director_ka' => 'required|regex:/^[აბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ.,!?\s]*$/',
            'release_year' => 'required',
            'budget' => 'required',
            'description_ka' => 'required|regex:/^[აბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ.,!?\s]*$/',
            'description_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
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
