<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class MakePrimaryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'verified'    => 'required',
        ];
    }
}
