<?php

namespace App\Policies;

use App\Models\Arquivo;
use App\Models\Inscricao;
use App\Models\Selecao;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class InscricaoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view their inscrições.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewTheir(User $user)
    {
        return Gate::allows('perfilusuario');
    }

    /**
     * Determine whether the user can view all inscrições.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente', 'perfildocente']);
    }

    /**
     * Determine whether the user can view the inscrição.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Inscricao  $inscricao
     * @return mixed
     */
    public function view(User $user, Inscricao $inscricao)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($inscricao->selecao->programa_id);
        elseif (Gate::allows('perfildocente'))
            return $user->gerenciaProgramaFuncao('Docentes do Programa', $inscricao->selecao->programa_id);
        else
            return ($inscricao->pessoas('Autor')->id == $user->id);    // permite que o usuário autor da inscrição a visualize
    }

    /**
     * Determine whether the user can create inscrições.
     *
     * @param  \App\User             $user
     * @param  ?\App\Models\Selecao  $selecao
     * @return mixed
     */
    public function create(User $user, ?Selecao $selecao = null)
    {
        if (!is_null($selecao)) {
            $selecao->atualizarStatus();
            if (!in_array($selecao->estado, ['Período de Solicitações de Isenção de Taxa e de Inscrições', 'Período de Inscrições']))
                return false;
        }

        return Gate::allows('perfilusuario');
    }

    /**
     * Determine whether the user can update the inscrição.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Inscricao  $inscricao
     * @return mixed
     */
    public function update(User $user, Inscricao $inscricao)
    {
        $selecao = $inscricao->selecao;
        $selecao->atualizarStatus();
        if (!in_array($selecao->estado, ['Período de Solicitações de Isenção de Taxa e de Inscrições', 'Período de Inscrições']))
            return false;

        return (Gate::allows('perfilusuario') && ($inscricao->pessoas('Autor')->id == $user->id));    // permite que apenas o usuário autor da inscrição a edite
    }

    /**
     * Determine whether the user can update the inscrição status.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Inscricao  $inscricao
     * @return mixed
     */
    public function updateStatus(User $user, Inscricao $inscricao)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($inscricao->selecao->programa_id);
        elseif (Gate::allows('perfildocente'))
            return false;
        else
            return false;
    }

    /**
     * Determine whether the user can update the inscrição arquivos.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Inscricao  $inscricao
     * @return mixed
     */
    public function updateArquivos(User $user, Inscricao $inscricao)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($inscricao->selecao->programa_id);
        elseif (Gate::allows('perfildocente'))
            return false;
        elseif (Gate::allows('perfilusuario'))
            return ($inscricao->pessoas('Autor')->id == $user->id);
    }

    /**
     * Determine whether the user can enviar um boleto.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Inscricao  $inscricao
     * @param  \App\Models\Arquivo    $arquivo
     * @return mixed
     */
    public function enviaBoleto(User $user, Inscricao $inscricao, Arquivo $arquivo)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($inscricao->selecao->programa_id);
        elseif (Gate::allows('perfildocente'))
            return false;
        else
            return false;
    }
}
