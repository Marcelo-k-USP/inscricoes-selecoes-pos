<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramaRequest extends FormRequest
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
            'nome' => ['required', 'max:100'],
            'descricao' => ['max:255'],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome do programa é obrigatório!',
            'nome.max' => 'O nome do programa não pode exceder 100 caracteres!',
            'descricao.max' => 'A descrição do programa não pode exceder 255 caracteres!',
        ];
    }
}
