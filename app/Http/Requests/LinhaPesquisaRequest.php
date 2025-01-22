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
    public const rules = [
        'nome' => ['required', 'max:100'],
        'programa_id' => ['required', 'integer'],
    ];

    public const messages = [
        'nome.required' => 'O nome da linha de pesquisa/tema é obrigatório!',
        'nome.max' => 'O nome da linha de pesquisa/tema não pode exceder 100 caracteres!',
        'programa_id.required' => 'O programa é obrigatório!',
        'programa_id.numeric' => 'O programa é inválido!',
    ];
}
