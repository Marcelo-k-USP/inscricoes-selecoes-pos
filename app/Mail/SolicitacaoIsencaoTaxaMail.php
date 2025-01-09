<?php

namespace App\Mail;

use App\Models\SolicitacaoIsencaoTaxa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitacaoIsencaoTaxaMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $solicitacaoisencaotaxa;

    protected $localuser;
    protected $email_confirmation_url;

    protected $user;
    protected $papel;
    protected $arquivo_nome;
    protected $arquivo_conteudo;
    protected $arquivo_erro;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->solicitacaoisencaotaxa = $data['solicitacaoisencaotaxa'];
        $this->localuser = $data['localuser'];
        $this->email_confirmation_url = $data['email_confirmation_url'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('[' . config('app.name') . '] Confirmação de E-mail')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.solicitacaoisencaotaxa_confirmacaodeemail')
            ->with([
                'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                'localuser' => $this->localuser,
                'email_confirmation_url' => $this->email_confirmation_url,
            ]);
    }
}
