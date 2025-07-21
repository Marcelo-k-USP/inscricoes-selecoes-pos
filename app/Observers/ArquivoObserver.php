<?php

namespace App\Observers;

use App\Mail\SelecaoMail;
use App\Models\Arquivo;

class ArquivoObserver
{
    /**
     * Handle the Arquivo "created" event.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return void
     */
    public function created(Arquivo $arquivo)
    {
        if (($arquivo->tipoarquivo->classe_nome == 'Seleções') && (in_array($arquivo->tipoarquivo->nome, ['Errata', 'Lista de Inscritos']))) {

            // envia e-mail para os candidatos avisando de novos documentos dos tipos Errata ou Lista de Inscritos na seleção
            // envio do e-mail "17" do README.md
            $passo = 'novo(s) informativo(s)';
            $selecao = $arquivo->selecoes()->first();
            $tipoarquivo = $arquivo->tipoarquivo->nome;
            foreach ($selecao->inscricoes->map(function ($inscricao) { return json_decode($inscricao->extras, true); })
                ->merge($selecao->solicitacoesisencaotaxa->map(function ($solicitacao) { return json_decode($solicitacao->extras, true); }))
                ->unique('e_mail') as $candidato) {
                $candidatonome = $candidato['nome'];
                \Mail::to($candidato['e_mail'])
                    ->queue(new SelecaoMail(compact('passo', 'selecao', 'candidatonome', 'tipoarquivo')));
            }
        }
    }

    /**
     * Listen to the Arquivo updating event.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return void
     */
    public function updating(Arquivo $arquivo)
    {
        //
    }

    /**
     * Handle the Arquivo "updated" event.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return void
     */
    public function updated(Arquivo $arquivo)
    {
        //
    }

    /**
     * Handle the Arquivo "deleted" event.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return void
     */
    public function deleted(Arquivo $arquivo)
    {
        //
    }

    /**
     * Handle the Arquivo "restored" event.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return void
     */
    public function restored(Arquivo $arquivo)
    {
        //
    }

    /**
     * Handle the Arquivo "force deleted" event.
     *
     * @param  \App\Models\Arquivo  $arquivo
     * @return void
     */
    public function forceDeleted(Arquivo $arquivo)
    {
        //
    }
}
