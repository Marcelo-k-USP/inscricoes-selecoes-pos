<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelecaoRequest extends FormRequest
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
        'categoria_id' => 'required|numeric',
        'nome' => ['required', 'max:100'],
        'descricao' => ['max:255'],
        'programa_id' => 'required|numeric',
    ];

    public const messages = [
        'categoria_id.required' => 'A categoria é obrigatória!',
        'categoria_id.numeric' => 'A categoria é inválida!',
        'nome.required' => 'O nome da seleção é obrigatório!',
        'nome.max' => 'O nome da seleção não pode exceder 100 caracteres!',
        'descricao.max' => 'A descrição da seleção não pode exceder 255 caracteres!',
        'programa_id.required' => 'O programa é obrigatório!',
        'programa_id.numeric' => 'O programa é inválido!',
    ];
}
