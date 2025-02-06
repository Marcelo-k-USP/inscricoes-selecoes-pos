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
        'obrigatorio' => [],
        'aluno_especial' => [],
    ];

    public const messages = [
        'classe_nome.required' => 'É obrigatório definir se para seleção, solicitação de isenção de taxa ou inscrição!',
        'nome.required' => 'O nome do tipo de arquivo é obrigatório!',
        'nome.max' => 'O nome do tipo de arquivo não pode exceder 100 caracteres!',
    ];
}
