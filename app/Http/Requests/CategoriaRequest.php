<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriaRequest extends FormRequest
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
        'descricao' => ['max:255'],
    ];

    public const messages =  [
        'nome.required' => 'O nome da categoria é obrigatório!',
        'nome.max' => 'O nome da categoria não pode exceder 100 caracteres!',
        'descricao.max' => 'A descrição da categoria não pode exceder 255 caracteres!',
    ];
}
