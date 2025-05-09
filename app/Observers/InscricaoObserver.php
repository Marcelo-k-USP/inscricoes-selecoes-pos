<?php

namespace App\Observers;

use App\Mail\InscricaoMail;
use App\Models\Disciplina;
use App\Models\Inscricao;
use App\Models\Parametro;
use App\Models\Programa;
use App\Services\BoletoService;
use Uspdev\Replicado\Pessoa;

class InscricaoObserver
{
    protected $boletoService;

    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

    /**
     * Handle the Inscricao "created" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function created(Inscricao $inscricao)
    {
        // envia e-mail avisando o candidato da necessidade de enviar os arquivos e enviar a própria inscrição
        $passo = 'início';
        $user = $inscricao->pessoas('Autor');
        \Mail::to($user->email)
            ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user')));
    }

    /**
     * Listen to the Inscricao updating event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function updating(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscricao "updated" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function updated(Inscricao $inscricao)
    {
        $user = $inscricao->pessoas('Autor');

        if ($inscricao->isDirty('estado')) {                                    // se a alteração na inscrição foi no estado
            if (($inscricao->getOriginal('estado') == 'Aguardando Envio') &&    // se o estado anterior era Aguardando Envio
                ($inscricao->estado == 'Enviada')) {                            // se o novo estado é Enviada

                if ($inscricao->selecao->tem_taxa && !$user->solicitacoesIsencaoTaxa()->where('selecao_id', $inscricao->selecao->id)->where('estado', 'Isenção de Taxa Aprovada')->exists()) {
                    $passo = 'boleto(s)';
                    $arquivos = [];
                    $email_secaoinformatica = Parametro::first()->email_secaoinformatica;
                    if ($inscricao->selecao->categoria->nome !== 'Aluno Especial')
                        $arquivos = [[
                            'nome' => 'boleto.pdf',
                            'conteudo' => $this->boletoService->gerarBoleto($inscricao),
                        ]];
                    else
                        foreach (Disciplina::whereIn('id', json_decode($inscricao->extras, true)['disciplinas'])->get() as $disciplina)
                            $arquivos[] = [
                                'nome' => 'boleto_' . strtolower($disciplina->sigla) . '.pdf',
                                'conteudo' => $this->boletoService->gerarBoleto($inscricao, ' - disciplina ' . $disciplina->sigla),
                            ];

                    // envia e-mail para o candidato com o(s) boleto(s)
                    \Mail::to($user->email)
                        ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'arquivos', 'email_secaoinformatica')));
                }

                $passo = 'realização';
                if ($inscricao->selecao->categoria->nome !== 'Aluno Especial') {
                    // envia e-mail avisando a secretaria do programa da seleção da inscrição sobre a realização da inscrição
                    $responsavel_nome = 'Prezados(as) Srs(as). da Secretaria do Programa ' . $inscricao->selecao->programa->nome;
                    \Mail::to($inscricao->selecao->programa->email_secretaria)
                        ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));

                    // envia e-mails avisando os coordenadores do programa da seleção da inscrição sobre a realização da inscrição
                    foreach (collect($inscricao->selecao->programa->obterResponsaveis())->firstWhere('funcao', 'Coordenadores do Programa')['users'] as $coordenador) {
                        $responsavel_nome = 'Prezado(a) Sr(a). ' . Pessoa::obterNome($coordenador->codpes);
                        \Mail::to($coordenador->email)
                            ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));
                    }
                } else {
                    // envia e-mails avisando o serviço de pós-graduação sobre a realização da inscrição
                    foreach (collect((new Programa)->obterResponsaveis())->firstWhere('funcao', 'Serviço de Pós-Graduação')['users'] as $servicoposgraduacao) {
                        $responsavel_nome = 'Prezado(a) Sr.(a) ' . Pessoa::obterNome($servicoposgraduacao->codpes);
                        \Mail::to($servicoposgraduacao->email)
                            ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));
                    }
                }
            } elseif (($inscricao->getOriginal('estado') == 'Em Pré-Avaliação') &&    // se o estado anterior era Em Pré-Avaliação
                      ($inscricao->estado == 'Pré-Aprovada')) {                       // se o novo estado é Pré-Aprovada

                // envia e-mail avisando o candidato da pré-aprovação da inscrição
                $passo = 'pré-aprovação';
                $link_acompanhamento = (($inscricao->selecao->categoria->nome == 'Aluno Especial') ? Parametro::first()->link_acompanhamento_especiais : $inscricao->selecao->programa->link_acompanhamento);
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'link_acompanhamento')));

            } elseif (($inscricao->getOriginal('estado') == 'Em Pré-Avaliação') &&    // se o estado anterior era Em Pré-Avaliação
                      ($inscricao->estado == 'Pré-Rejeitada')) {                      // se o novo estado é Pré-Rejeitada

                // envia e-mail avisando o candidato da pré-rejeição da inscrição
                $passo = 'pré-rejeição';
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user')));

            } elseif (($inscricao->getOriginal('estado') == 'Em Avaliação') &&        // se o estado anterior era Em Avaliação
                      (in_array($inscricao->estado, ['Aprovada', 'Rejeitada']))) {    // se o novo estado é Aprovada ou Rejeitada

                // envia e-mail avisando o candidato da aprovação/rejeição da inscrição
                $passo = (($inscricao->estado == 'Aprovada') ? 'aprovação' : 'rejeição');
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user')));
            }
        }
    }

    /**
     * Handle the Inscricao "deleted" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function deleted(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscricao "restored" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function restored(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscricao "force deleted" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function forceDeleted(Inscricao $inscricao)
    {
        //
    }
}
