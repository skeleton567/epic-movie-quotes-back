<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'    => 'required|min:3|exists:users',
            'password' => 'required',
        ];
    }
    public function messages()
    {
        return [
             'name.exists' => [
                'ka' => __('validation.incorect_credentians', ['attribute' => 'სახელი'], 'ka'),
                'en' => __('validation.incorect_credentians', ['attribute' => 'name'], 'en'),
             ],
        ];
    }
}
