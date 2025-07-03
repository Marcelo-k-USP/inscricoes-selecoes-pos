<?php

namespace App\Observers;

use App\Mail\SolicitacaoIsencaoTaxaMail;
use App\Models\Parametro;
use App\Models\SolicitacaoIsencaoTaxa;

class SolicitacaoIsencaoTaxaObserver
{
    /**
     * Handle the SolicitacaoIsencaoTaxa "created" event.
     *
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return void
     */
    public function created(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        // envia e-mail avisando o candidato da necessidade de enviar os arquivos e enviar a própria solicitação de isenção de taxa
        // envio do e-mail "4" do README.md
        $passo = 'início';
        $user = $solicitacaoisencaotaxa->pessoas('Autor');
        \Mail::to($user->email)
            ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user')));
    }

    /**
     * Listen to the SolicitacaoIsencaoTaxa updating event.
     *
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return void
     */
    public function updating(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        //
    }

    /**
     * Handle the SolicitacaoIsencaoTaxa "updated" event.
     *
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return void
     */
    public function updated(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        if ($solicitacaoisencaotaxa->isDirty('estado')) {                                    // se a alteração na solicitação de isenção de taxa foi no estado
            if (($solicitacaoisencaotaxa->getOriginal('estado') == 'Aguardando Envio') &&    // se o estado anterior era Aguardando Envio
                ($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Solicitada')) {         // se o novo estado é Isenção de Taxa Solicitada

                // envia e-mail avisando o serviço de pós-graduação sobre a solicitação da isenção de taxa
                // envio do e-mail "5" do README.md
                $passo = 'realização';
                $user = $solicitacaoisencaotaxa->pessoas('Autor');
                $servicoposgraduacao_nome = 'Prezados(as) Srs(as). do Serviço de Pós-Graduação';
                \Mail::to(Parametro::first()->email_servicoposgraduacao)
                    ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user', 'servicoposgraduacao_nome')));

            } elseif (($solicitacaoisencaotaxa->getOriginal('estado') == 'Isenção de Taxa em Avaliação') &&                      // se o estado anterior era Isenção de Taxa em Avaliação
                      in_array($solicitacaoisencaotaxa->estado, ['Isenção de Taxa Aprovada', 'Isenção de Taxa Rejeitada'])) {    // se o novo estado é Isenção de Taxa Aprovada ou Isenção de Taxa Rejeitada

                // envia e-mail avisando o candidato da aprovação/rejeição da solicitação de isenção de taxa
                // envio do e-mail "6" do README.md
                $passo = (($solicitacaoisencaotaxa->estado == 'Isenção de Taxa Aprovada') ? 'aprovação' : 'rejeição');
                $user = $solicitacaoisencaotaxa->pessoas('Autor');
                \Mail::to($user->email)
                    ->queue(new SolicitacaoIsencaoTaxaMail(compact('passo', 'solicitacaoisencaotaxa', 'user')));
            }
        }
    }

    /**
     * Handle the SolicitacaoIsencaoTaxa "deleted" event.
     *
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return void
     */
    public function deleted(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        //
    }

    /**
     * Handle the SolicitacaoIsencaoTaxa "restored" event.
     *
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return void
     */
    public function restored(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        //
    }

    /**
     * Handle the SolicitacaoIsencaoTaxa "force deleted" event.
     *
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return void
     */
    public function forceDeleted(SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        //
    }
}
