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

        // utilizamos markers para implementar o "E" lógico que o Laravel não fornece no 'required_if' (o Laravel só fornece o "OU" lógico)
        // se utilizássemos Rule::requiredIf, ocorreria o erro "Serialization of 'Closure' is not allowed" no momento de invocar views passando $rules nos compacts
        // não poderíamos remover as closures porque elas seriam necessárias para que as views common/list-table-form-* pudessem verificar se os campos são obrigatórios (verificando se $rule instanceof \Illuminate\Validation\Rules\RequiredIf)
        // com esta implementação atual fica mais simples: mantemos 'required_if' nos campos e, desta forma, as views common/list-table-form-* nem precisam ser alteradas, conseguem verificar sem maiores dificuldades se os campos são obrigatórios
        $this->merge([
            '_boleto_data_vencimento_required_marker' => ($this->input('tem_taxa') === 'on' && $this->input('fluxo_continuo') !== 'on') ? 1 : 0,
            '_boleto_offset_vencimento_required_marker' => ($this->input('tem_taxa') === 'on' && $this->input('fluxo_continuo') === 'on') ? 1 : 0,
        ]);

        return [
            'categoria_id' => ['required', 'numeric'],
            'programa_id' => ['required_unless:categoria_id,' . Categoria::where('nome', 'Aluno Especial')->value('id')],
            'nome' => ['required', 'max:100'],
            'descricao' => ['max:255'],
            'fluxo_continuo' => [],
            'tem_taxa' => [],
            'solicitacoesisencaotaxa_data_inicio' => ['required_if:tem_taxa,on'],
            'solicitacoesisencaotaxa_hora_inicio' => ['required_if:tem_taxa,on'],
            'solicitacoesisencaotaxa_data_fim' => ['required_if:tem_taxa,on'],
            'solicitacoesisencaotaxa_hora_fim' => ['required_if:tem_taxa,on'],
            'inscricoes_data_inicio' => ['required'],
            'inscricoes_hora_inicio' => ['required'],
            'inscricoes_data_fim' => ['required'],
            'inscricoes_hora_fim' => ['required'],
            'boleto_data_vencimento' => ['required_if:_boleto_data_vencimento_required_marker,1'],
            'boleto_offset_vencimento' => ['required_if:_boleto_offset_vencimento_required_marker,1'],
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
            'solicitacoesisencaotaxa_data_inicio.required_if' => 'A data de início das solicitações de isenção de taxa é obrigatória!',
            'solicitacoesisencaotaxa_hora_inicio.required_if' => 'A hora de início das solicitações de isenção de taxa é obrigatória!',
            'solicitacoesisencaotaxa_data_fim.required_if' => 'A data de fim das solicitações de isenção de taxa é obrigatória!',
            'solicitacoesisencaotaxa_hora_fim.required_if' => 'A hora de fim das solicitações de isenção de taxa é obrigatória!',
            'inscricoes_data_inicio.required' => 'A data de início das inscrições é obrigatória!',
            'inscricoes_hora_inicio.required' => 'A hora de início das inscrições é obrigatória!',
            'inscricoes_data_fim.required' => 'A data de fim das inscrições é obrigatória!',
            'inscricoes_hora_fim.required' => 'A hora de fim das inscrições é obrigatória!',
            'boleto_data_vencimento.required_if' => 'A data de vencimento do boleto é obrigatória!',
            'boleto_offset_vencimento.required_if' => 'A quantidade de dias úteis para pagamento do boleto é obrigatória!',
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
            '_boleto_data_vencimento_required_marker' => ($this->input('tem_taxa') === 'on' && $this->input('fluxo_continuo') !== 'on') ? 1 : 0,
            '_boleto_offset_vencimento_required_marker' => ($this->input('tem_taxa') === 'on' && $this->input('fluxo_continuo') === 'on') ? 1 : 0,
        ]);
    }
}
