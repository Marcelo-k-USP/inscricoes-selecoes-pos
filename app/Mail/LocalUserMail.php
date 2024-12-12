<?php

namespace App\Mail;

use App\Models\LocalUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocalUserMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $localuser;
    protected $password_reset_url;

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
            ->subject('[' . config('app.name') . '] Redefinição de Senha da Sua Conta')
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.localuser_redefinicaosenha')
            ->with([
                'localuser' => $this->localuser,
                'password_reset_url' => $this->password_reset_url,
            ]);
    }
}
