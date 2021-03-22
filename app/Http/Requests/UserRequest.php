<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'name' => 'required|max:150',
            'user_category_id' => 'required|integer',
            'document' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Campo nome é obrigatório',
            'user_category_id.required' => 'Categoria de usuário é obrigatório',
            'email.required' => 'Campo email é obrigatório',
            'document.required' => 'Campo documento é obrigatório',
            'password.required' => 'Campo senha é obrigatório',
            'document.unique' => 'CPF ou CNPJ em uso',
            'email.unique' => 'Email em uso',
        ];
    }
}
