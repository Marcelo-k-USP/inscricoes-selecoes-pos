<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MotivoIsencaoTaxaRequest extends FormRequest
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
    ];

    public const messages = [
        'nome.required' => 'O nome do motivo de isenção de taxa é obrigatório!',
        'nome.max' => 'O nome do motivo de isenção de taxa não pode exceder 100 caracteres!',
    ];
}
