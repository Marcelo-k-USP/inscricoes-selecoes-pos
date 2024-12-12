<?php

namespace App\Observers;

use App\Mail\InscricaoMail;
use App\Models\Inscricao;
use App\Services\BoletoService;

class InscricaoObserver
{
    protected $boletoService;

    public function __construct(BoletoService $boletoService)
    {
        $this->boletoService = $boletoService;
    }

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
        $user = \Auth::user();
        $autor = $inscricao->users()->wherePivot('papel', 'Autor')->first();
        $papel = 'Candidato';
        $arquivo_nome = 'boleto.pdf';
        $arquivo_conteudo = $this->boletoService->gerarBoleto($inscricao);

        // envia e-mail para o autor
        \Mail::to($user->email)
            ->queue(new InscricaoMail(compact('inscricao', 'user', 'autor', 'papel', 'arquivo_nome', 'arquivo_conteudo')));
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
