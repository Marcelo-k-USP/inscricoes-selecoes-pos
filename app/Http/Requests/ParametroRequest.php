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
        'link_acompanhamento_especiais' => ['max:255', 'nullable', 'url', 'regex:/^(http:\/\/|https:\/\/)/'],
        'email_servicoposgraduacao' => ['max:255', 'nullable', 'email'],
        'email_secaoinformatica' => ['max:255', 'nullable', 'email'],
    ];

    public const messages = [
        'boleto_codigo_fonte_recurso.required' => 'O código da fonte do recurso do boleto é obrigatório!',
        'boleto_codigo_fonte_recurso.integer' => 'O código da fonte do recurso do boleto é inválido!',
        'boleto_estrutura_hierarquica.required' => 'A estrutura hierárquica do boleto é obrigatória!',
        'boleto_estrutura_hierarquica.max' => 'A estrutura hierárquica do boleto não pode exceder 100 caracteres!',
        'link_acompanhamento_especiais.max' => 'O link de acompanhamento para alunos especiais não pode exceder 255 caracteres!',
        'link_acompanhamento_especiais.url' => 'O link de acompanhamento para alunos especiais deve ser uma URL válida.',
        'link_acompanhamento_especiais.regex' => 'O link de acompanhamento para alunos especiais deve começar com http:// ou https://',
        'email_servicoposgraduacao.max' => 'O e-mail do serviço de pós-graduação não pode exceder 255 caracteres!',
        'email_servicoposgraduacao.email' => 'O e-mail do serviço de pós-graduação deve ser válido.',
        'email_secaoinformatica.max' => 'O e-mail da seção de informática não pode exceder 255 caracteres!',
        'email_secaoinformatica.email' => 'O e-mail da seção de informática deve ser válido.',
    ];
}
