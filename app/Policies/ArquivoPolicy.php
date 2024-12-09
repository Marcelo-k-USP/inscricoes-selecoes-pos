<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ArquivoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(?User $user, Arquivo $model)    // se não colocarmos a interrogação, esta policy não é invocada no caso de usuário não logado
    {
        if ($model->selecoes()->where('estado', 'Em andamento')->exists())
            return true;                                 // permite que todos baixem arquivos de seleção

        if (Gate::allows('perfilusuario'))
            foreach ($arquivo->inscricoes as $inscricao) {
                $autor = $inscricao->pessoas('Autor');
                if ($autor && ($autor->id == $user->id))
                    return true;                         // permite que o usuário autor da inscrição baixe arquivos dessa inscrição
            }

        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Arquivo $model)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function update(User $user, Arquivo $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $user, Arquivo $model)
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
