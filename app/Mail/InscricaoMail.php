<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscricaoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $inscricao;
    protected $user;
    protected $autor;
    protected $papel;
    protected $arquivo_nome;
    protected $arquivo_conteudo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->inscricao = $data['inscricao'];
        $this->user = $data['user'];
        $this->autor = $data['autor'];
        $this->papel = $data['papel'];
        $this->arquivo_nome = $data['arquivo_nome'];
        $this->arquivo_conteudo = $data['arquivo_conteudo'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('[' . config('app.name') . '] Inscrição Realizada com Sucesso')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.inscricao_nova')
            ->with([
                'inscricao' => $this->inscricao,
                'user' => $this->user,
                'autor' => $this->autor,
                'papel' => $this->papel,
            ])
            ->attachData($this->arquivo_conteudo, $this->arquivo_nome, ['mime' => 'application/pdf']);
    }
}
