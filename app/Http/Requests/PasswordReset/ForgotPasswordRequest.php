<?php

namespace App\Http\Requests\PasswordReset;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users'
        ];
    }
    public function messages()
    {
        return [
             'email.exists' => [
                'ka' => __('passwords.user', ['attribute' => 'სახელი'], 'ka'),
                'en' => __('passwords.user', ['attribute' => 'name'], 'en'),
             ],
        ];
    }
}
