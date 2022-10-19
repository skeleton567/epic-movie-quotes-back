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
            'user_id' => 'required',
            'quote_id' => 'required',

        ];
    }
}
