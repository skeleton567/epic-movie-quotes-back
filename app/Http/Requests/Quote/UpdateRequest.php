<?php

namespace App\Http\Requests\Quote;

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
            'quote_en' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'quote_ka' => 'required|regex:/^[ა-ჰ.,!?\s]*$/',
        ];
    }
}
