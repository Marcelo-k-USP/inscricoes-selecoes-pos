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
        'boleto_codigo_fonte_recurso' => ['required', 'integer'],
        'boleto_estrutura_hierarquica' => ['required', 'max:100'],
        'email_servicoposgraduacao' => ['max:255', 'nullable', 'email'],
    ];

    public const messages = [
        'boleto_codigo_fonte_recurso.required' => 'O código da fonte do recurso do boleto é obrigatório!',
        'boleto_codigo_fonte_recurso.integer' => 'O código da fonte do recurso do boleto é inválido!',
        'boleto_estrutura_hierarquica.required' => 'A estrutura hierárquica do boleto é obrigatória!',
        'boleto_estrutura_hierarquica.max' => 'A estrutura hierárquica do boleto não pode exceder 100 caracteres!',
        'email_servicoposgraduacao.max' => 'O e-mail do serviço de pós-graduação não pode exceder 255 caracteres!',
        'email_servicoposgraduacao.email' => 'O e-mail do serviço de pós-graduação deve ser válido.',
    ];
}
