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

    // campos adicionais para 'envio - para candidato'

    // campos adicionais para 'envio - para gestores'
    protected $servicoposgraduacao_nome;

    // campos adicionais para 'aprovação', 'rejeição' e 'aprovação após recurso'

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
            case 'início':
            case 'envio - para candidato':
                break;

            case 'envio - para gestores':
                $this->servicoposgraduacao_nome = $data['servicoposgraduacao_nome'];
                break;

            case 'aprovação':
            case 'rejeição':
            case 'aprovação após recurso':
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
            case 'início':
                return $this
                    ->subject('[' . config('app.name') . '] Solicitação de Isenção de Taxa Pendente de Envio')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_inicio')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'user' => $this->user,
                    ]);

            case 'envio - para candidato':
                return $this
                    ->subject('[' . config('app.name') . '] Isenção de Taxa Solicitada')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_envio_paracandidato')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'user' => $this->user,
                    ]);

            case 'envio - para gestores':
                return $this
                    ->subject('[' . config('app.name') . '] Isenção de Taxa Solicitada')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_envio_paragestores')
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

            case 'aprovação após recurso':
                return $this
                    ->subject('[' . config('app.name') . '] Aprovação de Solicitação de Isenção de Taxa Após Recurso')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.solicitacaoisencaotaxa_aprovacaoaposrecurso')
                    ->with([
                        'solicitacaoisencaotaxa' => $this->solicitacaoisencaotaxa,
                        'user' => $this->user,
                    ]);
        }
    }
}
