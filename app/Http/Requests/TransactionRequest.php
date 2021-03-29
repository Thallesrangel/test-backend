<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'value' => 'required',
            'document' => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'Campo obrigatório',
            'value.required' => 'Informe o valor',
            'document.required' => 'Informe o documento do beneficiário'
        ];
    }
}
