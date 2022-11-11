<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNameRequest extends FormRequest
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
            'name' => 'required|min:3|max:15|unique:users',
        ];
    }
    public function messages()
    {
        return [
             'name.unique' => [
                'ka' => __('validation.unique', ['attribute' => 'სახელი'], 'ka'),
                'en' => __('validation.unique', ['attribute' => 'name'], 'en'),
             ],
        ];
    }
}
