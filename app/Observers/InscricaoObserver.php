<?php

namespace App\Observers;

use App\Mail\InscricaoMail;
use App\Models\Inscricao;

class InscricaoObserver
{
    /**
     * Handle the Inscrição "created" event.
     *
     * Ao criar uma inscrição, ela deve ser enviada para o autor.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function created(Inscricao $inscricao)
    {
        // vamos enviar e-mail para o autor
        $papel = 'Candidato';
        $user = \Auth::user();
        \Mail::to($user->email)
            ->queue(new InscricaoMail(compact('papel', 'user', 'inscricao')));
    }

    /**
     * Listen to the Inscrição updating event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function updating(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscrição "updated" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function updated(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscrição "deleted" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function deleted(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscrição "restored" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function restored(Inscricao $inscricao)
    {
        //
    }

    /**
     * Handle the Inscrição "force deleted" event.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return void
     */
    public function forceDeleted(Inscricao $inscricao)
    {
        //
    }
}
