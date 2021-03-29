<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'email.required' => 'Campo email é obrigatório',
            'password.required' => 'Campo senha é obrigatório'
        ];
    }
}
