<?php

namespace App\Policies;

use App\Models\LinhaPesquisa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class LinhaPesquisaPolicy
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
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User           $user
     * @param  \App\Models\LinhaPesquisa  $linhapesquisa
     * @return mixed
     */
    public function view(User $user, LinhaPesquisa $linhapesquisa)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($linhapesquisa->programa_id);
        else
            return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User           $user
     * @param  \App\Models\LinhaPesquisa  $linhapesquisa
     * @return mixed
     */
    public function update(User $user, LinhaPesquisa $linhapesquisa)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($linhapesquisa->programa_id);
        else
            return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User           $user
     * @param  \App\Models\LinhaPesquisa  $linhapesquisa
     * @return mixed
     */
    public function delete(User $user, LinhaPesquisa $linhapesquisa)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($linhapesquisa->programa_id);
        else
            return false;
    }
}
