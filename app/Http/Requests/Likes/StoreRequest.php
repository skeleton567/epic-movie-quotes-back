<?php

namespace App\Http\Requests\Likes;

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
            //
            'quote_id' => 'required',
            'user_to_notify' => 'required'
        ];
    }
}
