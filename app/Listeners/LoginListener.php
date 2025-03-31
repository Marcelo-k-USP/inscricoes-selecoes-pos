<?php

namespace App\Listeners;

use App\Models\Setor;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Uspdev\Replicado\Pessoa;

class LoginListener
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        $user = $event->user;
        if (!is_null($user->codpes)) {    // no caso de usuários sem codpes, tudo o que precisa ser feito já foi feito no LocalUserController

            $vinculos = Pessoa::listarVinculosAtivos($user->codpes, false);
            $log = 'login listener:';

            // vincula a pessoa ao setor
            session(['perfil' => '']);    // limpa para não errar dentro do listarProgramasGerenciados
            $possui_vinculo_gerente_acima_docente = false;
            $possui_vinculo_docente = false;
            foreach ($vinculos as $vinculo) {
                if ((!in_array(mb_convert_case($vinculo['tipvin'], MB_CASE_TITLE), ['Admin', 'Gerente', 'Docente'])) && !$user->listarProgramasGerenciados()->isEmpty())    // se o vínculo do usuário não for nem de admin nem de gerente nem de docente, e ele tiver alguma relação com algum programa...
                    if (!($user->listarProgramasGerenciados()->filter(function ($programa) {
                        return (!isset($programa->pivot) || ($programa->pivot->funcao !== 'Docentes do Programa'));
                    }))->isEmpty()) {
                        $vinculo['nomeVinculo'] = 'Gerente';    // iremos vinculá-lo ao seu setor como gerente, subindo seu grau de autorizações para que ele tenha acesso gerencial aos seus programas
                        $possui_vinculo_gerente_acima_docente = true;
                    } elseif (!$user->listarProgramasGerenciadosFuncao('Docentes do Programa')->isEmpty()) {
                        $vinculo['nomeVinculo'] = 'Docente';    // iremos vinculá-lo ao seu setor como docente, subindo seu grau de autorizações para que ele tenha acesso de docente aos seus programas
                        $possui_vinculo_docente = true;
                    }
                if ($setor = Setor::where('cod_set_replicado', $vinculo['codset'])->first())
                    Setor::vincularPessoa($setor, $user, mb_convert_case($vinculo['tipvin'], MB_CASE_TITLE));
            }

            // vamos manter a configuracao antiga para compatibilidade retroativa
            // mas deverá ser ajustado e removido as referências a "is_admin"
            // vamos verificar no config se o usuário é admin
            if (in_array($user->codpes, config('senhaunica.admins')))
                $user->is_admin = true;

            // atualiza o last login do usuário
            $user->last_login_at = now();
            $user->save();

            if ($user->is_admin || !$user->listarProgramasGerenciados()->isEmpty())
                session(['perfil' => ($user->is_admin ? 'admin' : ($possui_vinculo_gerente_acima_docente ? 'gerente' : ($possui_vinculo_docente ? 'docente' : 'usuario')))]);

            config('app.debug') && Log::info($log);
        }
    }
}
