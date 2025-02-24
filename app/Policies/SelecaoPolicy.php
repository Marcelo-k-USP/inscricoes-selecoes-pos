<?php

namespace App\Policies;

use App\Models\Selecao;
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
     * @param  \App\Models\User     $user
     * @param  \App\Models\Selecao  $selecao
     * @return mixed
     */
    public function view(User $user, Selecao $selecao)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($selecao->programa_id);
        else
            return false;
    }

    /**
     * Determine whether the user can create seleções.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can update the seleção.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Selecao  $selecao
     * @return mixed
     */
    public function update(User $user, Selecao $selecao)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($selecao->programa_id);
        else
            return false;
    }

    /**
     * Determine whether the user can update the seleção arquivos.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Selecao  $selecao
     * @return mixed
     */
    public function updateArquivos(User $user, Selecao $selecao)
    {
        if (Gate::allows('perfiladmin'))
            return true;
        elseif (Gate::allows('perfilgerente'))
            return $user->gerenciaPrograma($selecao->programa_id);
        else
            return false;
    }
}
