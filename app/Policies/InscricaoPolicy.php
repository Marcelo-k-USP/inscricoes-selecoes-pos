<?php

namespace App\Policies;

use App\Models\Inscricao;
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
        return Gate::any(['perfiladmin', 'perfilgerente']);
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
        if (Gate::allows('perfilusuario'))
            return ($inscricao->pessoas('Autor')->id == $user->id);    // permite que o usuário autor da inscrição a visualize

        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can create inscrições.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(?User $user = null)    // se não colocarmos a interrogação, esta policy não é invocada no caso de usuário não logado
    {
        if (is_null($user))
            return true;
        else
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
        return (Gate::allows('perfilusuario') && ($inscricao->pessoas('Autor')->id == $user->id));    // permite que apenas o usuário autor da inscrição a edite
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
