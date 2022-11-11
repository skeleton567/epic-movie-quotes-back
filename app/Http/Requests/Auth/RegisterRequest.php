<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'    => 'required|min:3|max:15|unique:users',
            'email'    => 'required|email|unique:users|unique:secondary_emails',
            'password' => 'required|min:8|max:15|confirmed',
        ];
    }
    public function messages()
    {
        return [
             'name.unique' => [
                'ka' => __('validation.unique', ['attribute' => 'სახელი'], 'ka'),
                'en' => __('validation.unique', ['attribute' => 'name'], 'en'),
             ],
            'email.unique' => [
                'en' => __('validation.unique', ['attribute' => 'email'], 'en'),
                'ka' => __('validation.unique', ['attribute' => 'იმეილი'], 'ka'),
             ],
        ];
    }
}
