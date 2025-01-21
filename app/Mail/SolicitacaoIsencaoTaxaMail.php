<?php

namespace App\Mail;

use App\Models\SolicitacaoIsencaoTaxa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitacaoIsencaoTaxaMail extends Mailable
{
    use Queueable, SerializesModels;

    // campos gerais
    protected $passo;
    protected $solicitacaoisencaotaxa;
    protected $user;

    // campos adicionais para solicitação de isenção de taxa realizada
    protected $servicoposgraduacao_nome;

    // campos adicionais para solicitação de isenção de taxa aprovada

    // campos adicionais para solicitação de isenção de taxa reprovada

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->passo = $data['passo'];
        $this->solicitacaoisencaotaxa = $data['solicitacaoisencaotaxa'];
        $this->user = $data['user'];

        switch ($this->passo) {
            case 'realização':
                $this->servicoposgraduacao_nome = $data['servicoposgraduacao_nome'];
                break;

            case 'aprovação':
                break;

            case 'rejeição':
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->passo) {
            case 'realização':
                return $this
                    ->subject('[' . config('app.name') . '] Solicitação de Isenção de Taxa')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_realizacao')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'servicoposgraduacao_nome' => $this->servicoposgraduacao_nome,
                    ]);

            case 'aprovação':
                return $this
                    ->subject('[' . config('app.name') . '] Aprovação de Solicitação de Isenção de Taxa')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_aprovacao')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'user' => $this->user,
                    ]);

            case 'rejeição':
                return $this
                    ->subject('[' . config('app.name') . '] Rejeição de Solicitação de Isenção de Taxa')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_rejeicao')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'user' => $this->user,
                    ]);
        }
    }
}
