<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InscricaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inscricao;
    public $autor;
    public $papel;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->inscricao = $data['inscricao'];
        $this->autor = $this->inscricao->users()->wherePivot('papel', 'Autor')->first();
        $this->papel = $data['papel'];
        $this->user = $data['user'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(
                '[' . config('app.name') . ']'
                . ' Inscrição Realizada com Sucesso'
            )
            ->view('emails.inscricao_nova');
    }
}
