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

    // campos adicionais para novo(s) informativo(s)
    protected $tipoarquivo;

    // campos adicionais para novo(s) informativo(s), alerta de proximidade do fim das solicitações de isenção de taxa e alerta de proximidade do fim das inscrições
    protected $candidatonome;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->passo = $data['passo'];
        $this->selecao = $data['selecao'];

        switch ($this->passo) {
            case 'novo(s) informativo(s)':
                $this->tipoarquivo = $data['tipoarquivo'];
                $this->candidatonome = $data['candidatonome'];
                break;

            case 'alerta de proximidade do fim das solicitações de isenção de taxa':
            case 'alerta de proximidade do fim das inscrições':
                $this->candidatonome = $data['candidatonome'];
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
            case 'seleção elaborada':
                return $this
                    ->subject('[' . config('app.name') . '] Seleção elaborada')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.selecao_elaborada')
                    ->with([
                        'selecao' => $this->selecao,
                    ]);

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
