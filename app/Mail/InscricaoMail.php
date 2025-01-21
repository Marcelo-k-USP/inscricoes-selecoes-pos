<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscricaoMail extends Mailable
{
    use Queueable, SerializesModels;

    // campos gerais
    protected $passo;
    protected $inscricao;
    protected $user;

    // campos adicionais para confirmação de e-mail
    protected $email_confirmation_url;

    // campos adicionais para boleto
    protected $papel;
    protected $arquivo_nome;
    protected $arquivo_conteudo;
    protected $arquivo_erro;

    // campos adicionais para inscrição realizada
    protected $secretario_nome;

    // campos adicionais para inscrição pré-aprovada
    protected $orientador_nome;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->passo = $data['passo'];
        $this->inscricao = $data['inscricao'];
        $this->user = $data['user'];

        switch ($this->passo) {
            case 'confirmação de e-mail':
                $this->email_confirmation_url = $data['email_confirmation_url'];
                break;

            case 'boleto':
                $this->papel = $data['papel'];
                $this->arquivo_nome = $data['arquivo_nome'];
                $this->arquivo_conteudo = $data['arquivo_conteudo'];
                $this->arquivo_erro = (!empty($this->arquivo_conteudo) ? '' : 'Ocorreu um erro na geração do boleto.<br />' . PHP_EOL .
                    'Por favor, entre em contato conosco em infor@ip.usp.br, informando-nos sobre esse problema.<br />' . PHP_EOL);
                break;

            case 'realização':
                $this->secretario_nome = $data['secretario_nome'];
                break;

            case 'pré-aprovação':
                $this->orientador_nome = $data['orientador_nome'];
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
            case 'confirmação de e-mail':
                return $this
                    ->subject('[' . config('app.name') . '] Confirmação de E-mail')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_confirmacaodeemail')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
                        'email_confirmation_url' => $this->email_confirmation_url,
                    ]);

            case 'boleto':
                return $this
                    ->subject('[' . config('app.name') . '] Inscrição Realizada com Sucesso')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_enviodeboleto')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
                        'papel' => $this->papel,
                        'arquivo_erro' => $this->arquivo_erro,
                    ])
                    ->when(!empty($this->arquivo_conteudo), function ($message) {
                        $message->attachData(base64_decode($this->arquivo_conteudo), $this->arquivo_nome, ['mime' => 'application/pdf']);
                    });

            case 'realização':
                return $this
                    ->subject('[' . config('app.name') . '] Realização de Inscrição')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_realizacao')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'secretario_nome' => $this->secretario_nome,
                    ]);

            case 'pré-aprovação':
                return $this
                    ->subject('[' . config('app.name') . '] Pré-Aprovação de Inscrição')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_preaprovacao')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'orientador_nome' => $this->orientador_nome,
                    ]);

            case 'aprovação':
                return $this
                    ->subject('[' . config('app.name') . '] Aprovação de Inscrição')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_aprovacao')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
                    ]);

            case 'rejeição':
                return $this
                    ->subject('[' . config('app.name') . '] Rejeição de Inscrição')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_rejeicao')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
                    ]);
        }
    }
}
