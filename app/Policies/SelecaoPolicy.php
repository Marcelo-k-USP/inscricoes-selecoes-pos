<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class SelecaoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view all seleções.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can view the seleção.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can create seleções.
     *
     * @param  \App\Models\User     $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can update the seleção.
     *
     * @param  \App\Models\User     $user
     * @return mixed
     */
    public function update(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can delete the seleção.
     *
     * @param  \App\Models\User     $user
     * @return mixed
     */
    public function delete(User $user)
    {
        //
    }

    /**
     * Determine whether the user can restore the seleção.
     *
     * @param  \App\Models\User     $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the seleção.
     *
     * @param  \App\Models\User     $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
