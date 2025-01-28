<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisciplinaRequest extends FormRequest
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
        'sigla' => ['required', 'max:20'],
        'nome' => ['required', 'max:100'],
    ];

    public const messages = [
        'sigla.required' => 'A sigla da disciplina é obrigatória!',
        'sigla.max' => 'A sigla da disciplina não pode exceder 20 caracteres!',
        'nome.required' => 'O nome da disciplina é obrigatório!',
        'nome.max' => 'O nome da disciplina não pode exceder 100 caracteres!',
    ];
}
