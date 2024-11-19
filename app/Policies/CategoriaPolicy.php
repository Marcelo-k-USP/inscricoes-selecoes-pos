<?php

namespace App\Policies;

use App\Models\Categoria;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class CategoriaPolicy
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
        # para admins
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Categoria  $categoria
     * @return mixed
     */
    public function view(User $user, Categoria $categoria)
    {
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
        /* admin */
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Categoria  $categoria
     * @return mixed
     */
    public function update(User $user, Categoria $categoria)
    {
        /* admin */
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Categoria  $categoria
     * @return mixed
     */
    public function delete(User $user, Categoria $categoria)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Categoria  $categoria
     * @return mixed
     */
    public function restore(User $user, Fila $fila)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User       $user
     * @param  \App\Models\Categoria  $categoria
     * @return mixed
     */
    public function forceDelete(User $user, Categoria $categoria)
    {
        //
    }
}
