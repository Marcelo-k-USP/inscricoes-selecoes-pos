<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametroRequest extends FormRequest
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
        'boleto_valor' => ['required', 'numeric', 'not_in:0'],
    ];

    public const messages = [
        'boleto_valor.required' => 'O valor do boleto é obrigatório!',
        'boleto_valor.numeric' => 'O valor do boleto é inválido!',
        'boleto_valor.not_in' => 'O valor do boleto não pode ser zero!',
    ];
}
