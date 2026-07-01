<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoArquivoRequest extends FormRequest
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
        'classe_nome' => ['required'],
        'nome' => ['required', 'max:100'],
        'abreviacao' => ['required', 'max:20'],
        'obrigatorio' => ['required'],
        'obrigatorio_condicao_campo' => ['nullable', 'required_if:obrigatorio,Condicional'],
        'obrigatorio_condicao_valor' => ['nullable', 'required_if:obrigatorio,Condicional'],
        'minimo' => ['nullable', 'integer'],
    ];

    public const messages = [
        'classe_nome.required' => 'É obrigatório definir se para seleção, solicitação de isenção de taxa, inscrição ou matrícula!',
        'nome.required' => 'O nome do tipo de documento é obrigatório!',
        'nome.max' => 'O nome do tipo de documento não pode exceder 100 caracteres!',
        'abreviacao.required' => 'A abreviação do tipo de documento é obrigatória!',
        'abreviacao.max' => 'A abreviação do tipo de documento não pode exceder 20 caracteres!',
        'obrigatorio.required' => 'O preenchimento da obrigatoriedade é obrigatório!',
        'obrigatorio_condicao_campo.required_if' => 'O campo da condição de obrigatoriedade é obrigatório quando ela for "Condicional"!',
        'obrigatorio_condicao_valor.required_if' => 'O valor da condição de obrigatoriedade é obrigatório quando ela for "Condicional"!',
        'minimo.integer' => 'A quantidade mínima deve ser um número inteiro!',
    ];
}
