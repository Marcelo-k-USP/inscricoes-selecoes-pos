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

    // campos adicionais para boleto(s)
    protected $papel;
    protected $arquivos;

    // campos adicionais para inscrição enviada
    protected $responsavel_nome;

    // campos adicionais para inscrição pré-rejeitada

    // campos adicionais para inscrição aprovada

    // campos adicionais para inscrição reprovada

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
            case 'início':
                break;

            case 'boleto(s)':
                $this->papel = $data['papel'];
                $this->arquivos = [];
                foreach ($data['arquivos'] as $data_arquivo)
                    $this->arquivos[] = [
                        'nome' => $data_arquivo['nome'],
                        'conteudo' => $data_arquivo['conteudo'],
                        'erro' => (!empty($data_arquivo['conteudo']) ? '' : 'Ocorreu um erro na geração do boleto "' . $data_arquivo['nome'] . '".<br />' . PHP_EOL .
                            'Por favor, entre em contato conosco em inforip@usp.br, informando-nos sobre esse problema.<br />' . PHP_EOL),
                    ];
                break;

            case 'realização':
                $this->responsavel_nome = $data['responsavel_nome'];
                break;

            case 'pré-rejeição':
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
            case 'início':
                return $this
                    ->subject('[' . config('app.name') . '] Inscrição Pendente de Envio')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_inicio')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
                    ]);

            case 'boleto(s)':
                $arquivos_erro = [];
                foreach ($this->arquivos as $arquivo)
                    $arquivos_erro[] = $arquivo['erro'];
                $mail = $this
                    ->subject('[' . config('app.name') . '] Inscrição Enviada')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_enviodeboletos')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
                        'papel' => $this->papel,
                        'arquivos_count' => count($this->arquivos),
                        'arquivos_erro' => $arquivos_erro,
                    ]);
                foreach ($this->arquivos as $arquivo)
                    if (!empty($arquivo['conteudo']))
                        $mail->attachData(base64_decode($arquivo['conteudo']), $arquivo['nome'], ['mime' => 'application/pdf']);
                return $mail;

            case 'realização':
                return $this
                    ->subject('[' . config('app.name') . '] Realização de Inscrição')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_realizacao')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'responsavel_nome' => $this->responsavel_nome,
                    ]);

            case 'pré-rejeição':
                return $this
                    ->subject('[' . config('app.name') . '] Rejeição de Inscrição')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.inscricao_prerejeicao')
                    ->with([
                        'inscricao' => $this->inscricao,
                        'user' => $this->user,
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
