<?php

namespace App\Observers;

use App\Mail\SelecaoMail;
use App\Models\Parametro;
use App\Models\Selecao;

class SelecaoObserver
{
    /**
     * Handle the Selecao "created" event.
     *
     * @param  \App\Models\Selecao  $selecao
     * @return void
     */
    public function created(Selecao $selecao)
    {
        //
    }

    /**
     * Listen to the Selecao updating event.
     *
     * @param  \App\Models\Selecao  $selecao
     * @return void
     */
    public function updating(Selecao $selecao)
    {
        //
    }

    /**
     * Handle the Selecao "updated" event.
     *
     * @param  \App\Models\Selecao  $selecao
     * @return void
     */
    public function updated(Selecao $selecao)
    {
        if ($selecao->isDirty('estado'))                                           // se a alteração na seleção foi no estado
            if (($selecao->getOriginal('estado') == 'Em Elaboração') &&            // se o estado anterior era Em Elaboração
                (str_starts_with($selecao->estado, 'Aguardando Início das ') ||    // se o novo estado é algum desses
                 str_starts_with($selecao->estado, 'Período de '))) {

                // envia e-mail avisando o gerenciamento do site da unidade sobre a seleção
                // envio do e-mail "1" do README.md
                $passo = 'seleção elaborada';
                \Mail::to(Parametro::first()->email_gerenciamentosite)
                    ->queue(new SelecaoMail(compact('passo', 'selecao')));
            }
    }

    /**
     * Handle the Selecao "deleted" event.
     *
     * @param  \App\Models\Selecao  $selecao
     * @return void
     */
    public function deleted(Selecao $selecao)
    {
        //
    }

    /**
     * Handle the Selecao "restored" event.
     *
     * @param  \App\Models\Selecao  $selecao
     * @return void
     */
    public function restored(Selecao $selecao)
    {
        //
    }

    /**
     * Handle the Selecao "force deleted" event.
     *
     * @param  \App\Models\Selecao  $selecao
     * @return void
     */
    public function forceDeleted(Selecao $selecao)
    {
        //
    }
}
