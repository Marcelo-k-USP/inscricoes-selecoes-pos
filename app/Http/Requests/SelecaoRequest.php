<?php

namespace App\Http\Requests;

use App\Models\Categoria;
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
    public function rules() {
        return [
            'categoria_id' => ['required', 'numeric'],
            'programa_id' => ['required_unless:categoria_id,' . Categoria::where('nome', 'Aluno Especial')->value('id')],
            'nome' => ['required', 'max:100'],
            'descricao' => ['max:255'],
            'data_inicio' => ['required'],
            'hora_inicio' => ['required'],
            'data_fim' => ['required'],
            'hora_fim' => ['required'],
            'tem_taxa' => [],
            'boleto_data_vencimento' => ['required_if:tem_taxa,on'],
            'boleto_valor' => ['required_if:tem_taxa,on', 'numeric'],
            'boleto_texto' => ['max:255'],
            'email_inscricaoaprovacao_texto' => ['max:255'],
            'email_inscricaorejeicao_texto' => ['max:255'],
        ];
    }

    public function messages() {
        return [
            'categoria_id.required' => 'A categoria é obrigatória!',
            'categoria_id.numeric' => 'A categoria é inválida!',
            'programa_id.required_unless' => 'O programa é obrigatório!',
            'nome.required' => 'O nome da seleção é obrigatório!',
            'nome.max' => 'O nome da seleção não pode exceder 100 caracteres!',
            'descricao.max' => 'A descrição da seleção não pode exceder 255 caracteres!',
            'data_inicio.required' => 'A data de início é obrigatória!',
            'hora_inicio.required' => 'A hora de início é obrigatória!',
            'data_fim.required' => 'A data de fim é obrigatória!',
            'hora_fim.required' => 'A hora de fim é obrigatória!',
            'boleto_data_vencimento.required_if' => 'A data de vencimento do boleto é obrigatória!',
            'boleto_valor.required_if' => 'O valor do boleto é obrigatório!',
            'boleto_valor.numeric' => 'O valor do boleto é inválido!',
            'boleto_texto.max' => 'O texto do boleto não pode exceder 255 caracteres!',
            'email_inscricaoaprovacao_texto.max' => 'O texto do e-mail de aprovação da inscrição não pode exceder 255 caracteres!',
            'email_inscricaorejeicao_texto.max' => 'O texto do e-mail de rejeição da inscrição não pode exceder 255 caracteres!',
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'boleto_valor' => str_replace(',', '.', $this->boleto_valor),
        ]);
    }
}
