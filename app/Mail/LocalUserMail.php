<?php

namespace App\Mail;

use App\Models\LocalUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LocalUserMail extends Mailable
{
    use Queueable, SerializesModels;

    // campos gerais
    protected $passo;
    protected $localuser;

    // campos adicionais para confirmação de e-mail
    protected $email_confirmation_url;

    // campos adicionais para reset de senha
    protected $password_reset_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->passo = $data['passo'];
        $this->localuser = $data['localuser'];

        switch ($this->passo) {
            case 'confirmação de e-mail':
                $this->email_confirmation_url = $data['email_confirmation_url'];
                break;

            case 'reset de senha':
                $this->password_reset_url = $data['password_reset_url'];
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
                        'localuser' => $this->localuser,
                        'email_confirmation_url' => $this->email_confirmation_url,
                    ]);

            case 'reset de senha':
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
}
