<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinhaPesquisaRequest extends FormRequest
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
            'codpes_docente' => ['required', 'integer'],
            'programa_id' => ['required', 'integer'],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'nome.required' => 'O nome da linha de pesquisa é obrigatório!',
            'nome.max' => 'O nome da linha de pesquisa não pode exceder 100 caracteres!',
            'codpes_docente.required' => 'O docente responsável é obrigatório!',
            'programa_id.required' => 'O programa é obrigatório!',
            'programa_id.numeric' => 'O programa é inválido!',
        ];
    }
}
