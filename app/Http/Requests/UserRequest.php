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
        if ($this->route()->getActionMethod() == 'update') {
            $rules = [
                'name' => 'required|max:150',
                'id_user_category' => 'required|integer',
                'document' => 'required',
                'email' => 'required|email',
                'password' => 'required|max:50'
            ];
        } else {
            $rules = [
                'name' => 'required|max:150',
                'id_user_category' => 'required|integer',
                'document' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|max:50'
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'name.required' => 'Campo nome é obrigatório',
            'id_user_category.required' => 'Categoria de usuário é obrigatório',
            'email.required' => 'Campo email é obrigatório',
            'document.required' => 'Campo documento é obrigatório',
            'password.required' => 'Campo senha é obrigatório',
            'document.unique' => 'CPF ou CNPJ em uso',
            'email.unique' => 'Email em uso',
        ];
    }
}
