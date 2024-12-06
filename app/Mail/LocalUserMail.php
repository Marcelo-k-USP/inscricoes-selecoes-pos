<?php

namespace App\Mail;

use App\Models\LocalUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocalUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $localuser;
    public $password_reset_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->localuser = $data['localuser'];
        $this->password_reset_url = $data['password_reset_url'];
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
                . ' Redefinição de Senha da Sua Conta'
            )
            ->view('emails.localuser_redefinicaosenha');
    }
}
