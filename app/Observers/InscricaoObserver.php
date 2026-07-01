<?php

namespace App\Observers;

use App\Mail\InscricaoMail;
use App\Models\Inscricao;
use App\Models\Parametro;
use App\Models\Programa;
use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\TipoArquivo;
use App\Models\User;
use App\Services\BoletoService;
use Illuminate\Support\Facades\DB;
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
        // envio do e-mail "8" do README.md
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
        $extras = json_decode($inscricao->extras, true);
        $arquivos = [];
        $boleto_momento_envio = Parametro::first()->boleto_momento_envio;
        $email_secaoinformatica = Parametro::first()->email_secaoinformatica;

        if ($inscricao->isDirty('estado')) {                                    // se a alteração na inscrição foi no estado
            if (($inscricao->getOriginal('estado') == 'Aguardando Envio') &&    // se o estado anterior era Aguardando Envio
                ($inscricao->estado == 'Enviada')) {                            // se o novo estado é Enviada
                // trata-se do envio da inscrição

                // envia e-mail para o candidato reconhecendo que ele enviou a inscrição
                // envio do e-mail "9" do README.md
                $passo = 'envio - para candidato';
                if ($inscricao->selecao->tem_taxa &&
                    ($boleto_momento_envio == 'Envio da Inscrição/Matrícula') &&
                    !SolicitacaoIsencaoTaxa::where('extras->cpf', $extras['cpf'] ?? null)->where('selecao_id', $inscricao->selecao->id)->where('estado', 'LIKE', 'Isenção de Taxa Aprovada%')->exists())
                    if ($inscricao->selecao->categoria->nome !== 'Aluno Especial')
                        $arquivos = [$this->boletoService->gerarBoleto($inscricao, 'Inscricao')];    // gera boleto para a inscrição
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'arquivos', 'email_secaoinformatica')));

                // envia e-mail avisando a secretaria do programa da seleção da inscrição sobre a realização da inscrição
                // envio do e-mail "10" do README.md
                $passo = 'envio - para gestores';
                $responsavel_nome = 'Prezados(as) Srs(as). da Secretaria do Programa ' . $inscricao->selecao->programa->nomeCompleto();
                \Mail::to($inscricao->selecao->programa->email_secretaria)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));

                // envia e-mails avisando os coordenadores do programa da seleção da inscrição sobre a realização da inscrição
                // envio do e-mail "11" do README.md
                foreach (collect($inscricao->selecao->programa->obterResponsaveis())->firstWhere('funcao', 'Coordenadores do Programa')['users'] as $coordenador) {
                    $responsavel_nome = 'Prezado(a) Sr(a). ' . Pessoa::obterNome($coordenador->codpes);
                    \Mail::to($coordenador->email)
                        ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'responsavel_nome')));
                }
            } elseif (($inscricao->getOriginal('estado') == 'Em Pré-Avaliação') &&    // se o estado anterior era Em Pré-Avaliação
                      ($inscricao->estado == 'Pré-Aprovada')) {                       // se o novo estado é Pré-Aprovada
                // trata-se da pré-aprovação da inscrição

                // envia e-mail avisando o candidato da pré-aprovação da inscrição
                // envio do e-mail "15" do README.md
                $passo = 'pré-aprovação';
                $link_acompanhamento = $inscricao->selecao->programa->link_acompanhamento;
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'link_acompanhamento')));

            } elseif (($inscricao->getOriginal('estado') == 'Em Pré-Avaliação') &&    // se o estado anterior era Em Pré-Avaliação
                      ($inscricao->estado == 'Pré-Rejeitada')) {                      // se o novo estado é Pré-Rejeitada
                // trata-se da pré-rejeição da inscrição

                // envia e-mail avisando o candidato da pré-rejeição da inscrição
                // envio do e-mail "16" do README.md
                $passo = 'pré-rejeição';
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user')));

            } elseif (($inscricao->getOriginal('estado') == 'Em Avaliação') &&        // se o estado anterior era Em Avaliação
                      ($inscricao->estado == 'Aprovada')) {                           // se o novo estado é Aprovada
                // trata-se da aprovação da inscrição

                // verifica se a seleção tem taxa e se o candidato não tem isenção de taxa aprovada
                if ($inscricao->selecao->tem_taxa &&
                    ($boleto_momento_envio == 'Aprovação da Inscrição/Matrícula') &&
                    !SolicitacaoIsencaoTaxa::where('extras->cpf', $extras['cpf'] ?? null)->where('selecao_id', $inscricao->selecao->id)->where('estado', 'LIKE', 'Isenção de Taxa Aprovada%')->exists())
                    if ($inscricao->selecao->categoria->nome !== 'Aluno Especial')
                        $arquivos = [$this->boletoService->gerarBoleto($inscricao, 'Inscricao')];    // gera boleto para a inscrição

                // envia e-mail avisando o candidato da aprovação da inscrição
                // envio do e-mail "17" do README.md
                $passo = 'aprovação';
                \Mail::to($user->email)
                    ->queue(new InscricaoMail(compact('passo', 'inscricao', 'user', 'arquivos', 'email_secaoinformatica')));

            } elseif (($inscricao->getOriginal('estado') == 'Em Avaliação') &&        // se o estado anterior era Em Avaliação
                      ($inscricao->estado == 'Rejeitada')) {                          // se o novo estado é Rejeitada
                // trata-se da rejeição da inscrição

                // envia e-mail avisando o candidato da rejeição da inscrição
                // envio do e-mail "18" do README.md
                $passo = 'rejeição';
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
