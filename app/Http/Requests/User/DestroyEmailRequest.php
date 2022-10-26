<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DestroyEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required',
        ];
    }
}
