<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscricaoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $passo;
    protected $inscricao;

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
        $this->passo = $data['passo'];
        $this->inscricao = $data['inscricao'];
        switch ($this->passo) {
            case 'confirmação de e-mail':
                $this->localuser = $data['localuser'];
                $this->email_confirmation_url = $data['email_confirmation_url'];
                break;
            case 'boleto':
                $this->user = $data['user'];
                $this->papel = $data['papel'];
                $this->arquivo_nome = $data['arquivo_nome'];
                $this->arquivo_conteudo = $data['arquivo_conteudo'];
                $this->arquivo_erro = (!empty($this->arquivo_conteudo) ? '' : 'Ocorreu um erro na geração do boleto.<br />' . PHP_EOL .
                    'Por favor, entre em contato conosco em infor@ip.usp.br, informando-nos sobre esse problema.<br />' . PHP_EOL);
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
                        'localuser' => $this->localuser,
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
        }
    }
}
