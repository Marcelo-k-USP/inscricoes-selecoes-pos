<?php

namespace App\Mail;

use App\Models\Selecao;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SelecaoMail extends Mailable
{
    use Queueable, SerializesModels;

    // campos gerais
    protected $passo;
    protected $selecao;
    protected $candidatonome;

    // campos adicionais para novo(s) informativo(s)
    protected $tipoarquivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->passo = $data['passo'];
        $this->selecao = $data['selecao'];
        $this->candidatonome = $data['candidatonome'];

        switch ($this->passo) {
            case 'novo(s) informativo(s)':
                $this->tipoarquivo = $data['tipoarquivo'];
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
            case 'novo(s) informativo(s)':
                switch ($this->tipoarquivo) {
                    case 'Errata':
                        return $this
                            ->subject('[' . config('app.name') . '] Publicação de Errata')
                            ->from(config('mail.from.address'), config('mail.from.name'))
                            ->view('emails.selecao_publicacaoerrata')
                            ->with([
                                'selecao' => $this->selecao,
                                'candidatonome' => $this->candidatonome,
                            ]);

                    case 'Lista de Inscritos':
                        return $this
                            ->subject('[' . config('app.name') . '] Publicação de Lista de Inscritos')
                            ->from(config('mail.from.address'), config('mail.from.name'))
                            ->view('emails.selecao_publicacaolistainscritos')
                            ->with([
                                'selecao' => $this->selecao,
                                'candidatonome' => $this->candidatonome,
                            ]);
                }
                break;

            case 'alerta de proximidade do fim das solicitações de isenção de taxa':
                return $this
                    ->subject('[' . config('app.name') . '] Solicitação de Isenção de Taxa não enviada')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.selecao_solicitacaoisencaotaxanaoconcluida')
                    ->with([
                        'selecao' => $this->selecao,
                        'candidatonome' => $this->candidatonome,
                    ]);

                case 'alerta de proximidade do fim das inscrições':
                    return $this
                        ->subject('[' . config('app.name') . '] Inscrição não enviada')
                        ->from(config('mail.from.address'), config('mail.from.name'))
                        ->view('emails.selecao_inscricaonaoconcluida')
                        ->with([
                            'selecao' => $this->selecao,
                            'candidatonome' => $this->candidatonome,
                        ]);
        }
    }
}
