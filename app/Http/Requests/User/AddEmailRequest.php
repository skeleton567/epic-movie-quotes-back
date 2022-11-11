<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AddEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|unique:users|unique:secondary_emails',
            'user_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.unique' => [
                'en' => __('validation.unique', ['attribute' => 'email'], 'en'),
                'ka' => __('validation.unique', ['attribute' => 'იმეილი'], 'ka'),
             ],
        ];
    }
}
