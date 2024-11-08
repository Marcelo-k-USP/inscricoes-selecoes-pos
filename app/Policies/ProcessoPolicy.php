<?php

namespace App\Policies;

use App\Models\Processo;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ProcessoPolicy
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
        return true;

        # para admins
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User      $user
     * @param  \App\Models\Processo  $processo
     * @return mixed
     */
    public function view(User $user, Processo $processo)
    {
        return true;

        /* admin */
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User      $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;

        /* admin */
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User      $user
     * @param  \App\Models\Processo  $processo
     * @return mixed
     */
    public function update(User $user, Processo $processo)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User      $user
     * @param  \App\Models\Processo  $processo
     * @return mixed
     */
    public function delete(User $user, Processo $processo)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User      $user
     * @param  \App\Models\Processo  $processo
     * @return mixed
     */
    public function restore(User $user, Fila $fila)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User      $user
     * @param  \App\Models\Processo  $processo
     * @return mixed
     */
    public function forceDelete(User $user, Processo $processo)
    {
        //
    }
}
