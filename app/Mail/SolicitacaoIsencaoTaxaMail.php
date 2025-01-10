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

    // campos adicionais para confirmação de e-mail
    protected $email_confirmation_url;

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
            case 'confirmação de e-mail':
                $this->email_confirmation_url = $data['email_confirmation_url'];
                break ;
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
            case 'confirmação de e-mail':
                return $this
                    ->subject('[' . config('app.name') . '] Confirmação de E-mail')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_confirmacaodeemail')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'user' => $this->user,
                        'email_confirmation_url' => $this->email_confirmation_url,
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
