<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class LimpezaDadosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access the limpeza de dados functionality.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function showForm(User $user)
    {
        // Permite acesso apenas para administradores.
        return Gate::allows('perfiladmin');
    }

    /**
     * Determine whether the user can execute the limpeza de dados action.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function run(User $user)
    {
        // Apenas administradores podem executar a limpeza.
        return Gate::allows('perfiladmin');
    }
}
