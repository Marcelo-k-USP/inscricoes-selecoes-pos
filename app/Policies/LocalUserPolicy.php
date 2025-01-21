<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class LocalUserPolicy
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
        return Gate::allows('perfiladmin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User   $user
     * @return mixed
     */
    public function view(User $user)
    {
        return Gate::allows('perfiladmin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\?User  $user
     * @return mixed
     */
    public function create(?User $user)    // se não colocarmos a interrogação, esta policy não é invocada no caso de usuário não logado
    {
        return (is_null($user) || Gate::allows('perfiladmin'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User   $user
     * @return mixed
     */
    public function update(User $user)
    {
        return Gate::allows('perfiladmin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User   $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return Gate::allows('perfiladmin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User   $user
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User   $user
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
