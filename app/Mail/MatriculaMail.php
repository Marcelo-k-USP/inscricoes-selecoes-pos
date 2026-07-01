<?php

namespace App\Mail;

use App\Models\Matricula;
use App\Models\Parametro;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatriculaMail extends Mailable
{
    use Queueable, SerializesModels;

    // campos gerais
    protected $passo;
    protected $matricula;
    protected $user;
    protected $boleto_momento_envio;

    // campos adicionais para 'envio - para candidato', 'envio disciplinas alteradas - para candidato' e 'aprovação'
    protected $arquivos;

    // campos adicionais para 'boleto - envio manual'
    protected $arquivo;

    // campos adicionais para 'envio - para gestores'
    protected $responsavel_nome;

    // campos adicionais para 'pré-aprovação'
    protected $link_acompanhamento;

    // campos adicionais para 'pré-rejeição'

    // campos adicionais para 'rejeição'

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->passo = $data['passo'];
        $this->matricula = $data['matricula'];
        $this->user = $data['user'];
        $this->boleto_momento_envio = Parametro::first()->boleto_momento_envio;

        switch ($this->passo) {
            case 'início':
                break;

            case 'envio - para candidato':
            case 'envio disciplinas alteradas - para candidato':
                $this->arquivos = [];
                if ($this->boleto_momento_envio == 'Envio da Inscrição/Matrícula')
                    foreach ($data['arquivos'] as $data_arquivo)
                        $this->arquivos[] = [
                            'nome_original' => $data_arquivo['nome_original'],
                            'conteudo' => $data_arquivo['conteudo'],
                            'erro' => (!empty($data_arquivo['conteudo']) ? '' : 'Ocorreu um erro na geração do boleto "' . $data_arquivo['nome_original'] . '".<br />' . PHP_EOL .
                                                                                (!empty($data['email_secaoinformatica']) ? 'Por favor, entre em contato conosco em ' . $data['email_secaoinformatica'] . ', informando-nos sobre esse problema.<br />' . PHP_EOL : '')),
                    ];
                break;

            case 'boleto - envio manual':
                $this->arquivo = [
                    'nome_original' => $data['arquivo']->nome_original,
                    'conteudo' => $data['arquivo']->conteudo,
                ];
                break;

            case 'envio - para gestores':
                $this->responsavel_nome = $data['responsavel_nome'];
                break;

            case 'pré-aprovação':
                $this->link_acompanhamento = $data['link_acompanhamento'];
                break;

            case 'pré-rejeição':
                break;

            case 'aprovação':
                $this->arquivos = [];
                if ($this->boleto_momento_envio == 'Aprovação da Inscrição/Matrícula')
                    foreach ($data['arquivos'] as $data_arquivo)
                        $this->arquivos[] = [
                            'nome_original' => $data_arquivo['nome_original'],
                            'conteudo' => $data_arquivo['conteudo'],
                            'erro' => (!empty($data_arquivo['conteudo']) ? '' : 'Ocorreu um erro na geração do boleto "' . $data_arquivo['nome_original'] . '".<br />' . PHP_EOL .
                                                                                (!empty($data['email_secaoinformatica']) ? 'Por favor, entre em contato conosco em ' . $data['email_secaoinformatica'] . ', informando-nos sobre esse problema.<br />' . PHP_EOL : '')),
                    ];
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
                    ->subject('[' . config('app.name') . '] ' . 'Matrícula Pendente de Envio')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_inicio')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                    ]);

            case 'envio - para candidato':
            case 'envio disciplinas alteradas - para candidato':
                $arquivos_erro = [];
                foreach ($this->arquivos as $arquivo)
                    $arquivos_erro[] = $arquivo['erro'];
                $mail = $this
                    ->subject('[' . config('app.name') . '] ' . 'Matrícula Enviada')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_envio' . (($this->passo == 'envio disciplinas alteradas - para candidato') ? 'disciplinasalteradas' : '') . '_paracandidato')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                        'arquivos_count' => count($this->arquivos),
                        'arquivos_erro' => $arquivos_erro,
                        'boleto_momento_envio' => $this->boleto_momento_envio,
                    ]);
                foreach ($this->arquivos as $arquivo)
                    if (!empty($arquivo['conteudo']))
                        $mail->attachData(base64_decode($arquivo['conteudo']), $arquivo['nome_original'], ['mime' => 'application/pdf']);
                return $mail;

            case 'boleto - envio manual':
                $mail = $this
                    ->subject('[' . config('app.name') . '] Boleto Enviado')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_enviomanualdeboleto')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                    ]);
                if (!empty($this->arquivo['conteudo']))
                    $mail->attachData(base64_decode($this->arquivo['conteudo']), $this->arquivo['nome_original'], ['mime' => 'application/pdf']);
                return $mail;

            case 'envio - para gestores':
                return $this
                    ->subject('[' . config('app.name') . '] Realização de Matrícula')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_envio_paragestores')
                    ->with([
                        'matricula' => $this->matricula,
                        'responsavel_nome' => $this->responsavel_nome,
                    ]);

            case 'pré-aprovação':
                return $this
                    ->subject('[' . config('app.name') . '] Acompanhamento de Matrícula')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_preaprovacao')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                        'link_acompanhamento' => $this->link_acompanhamento,
                    ]);

            case 'pré-rejeição':
                return $this
                    ->subject('[' . config('app.name') . '] Rejeição de Matrícula')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_prerejeicao')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                    ]);

            case 'aprovação':
                $arquivos_erro = [];
                foreach ($this->arquivos as $arquivo)
                    $arquivos_erro[] = $arquivo['erro'];
                $mail = $this
                    ->subject('[' . config('app.name') . '] Aprovação de Matrícula')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_aprovacao')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                        'arquivos_count' => count($this->arquivos),
                        'arquivos_erro' => $arquivos_erro,
                        'boleto_momento_envio' => $this->boleto_momento_envio,
                    ]);
                foreach ($this->arquivos as $arquivo)
                    if (!empty($arquivo['conteudo']))
                        $mail->attachData(base64_decode($arquivo['conteudo']), $arquivo['nome_original'], ['mime' => 'application/pdf']);
                return $mail;

            case 'rejeição':
                return $this
                    ->subject('[' . config('app.name') . '] Rejeição de Matrícula')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.matricula_rejeicao')
                    ->with([
                        'matricula' => $this->matricula,
                        'user' => $this->user,
                    ]);
        }
    }
}
