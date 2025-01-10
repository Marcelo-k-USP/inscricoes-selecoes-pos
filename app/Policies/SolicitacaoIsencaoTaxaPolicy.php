<?php

namespace App\Policies;

use App\Models\SolicitacaoIsencaoTaxa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class SolicitacaoIsencaoTaxaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view their solicitações de isenção de taxa.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewTheir(User $user)
    {
        return Gate::allows('perfilusuario');
    }

    /**
     * Determine whether the user can view all solicitações de isenção de taxa.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can view the solicitação de isenção de taxa.
     *
     * @param  \App\Models\User                    $user
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return mixed
     */
    public function view(User $user, SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        if (Gate::allows('perfilusuario'))
            return ($solicitacaoisencaotaxa->pessoas('Autor')->id == $user->id);    // permite que o usuário autor da solicitação de isenção de taxa a visualize

        return Gate::any(['perfiladmin', 'perfilgerente']);
    }

    /**
     * Determine whether the user can create solicitações de isenção de taxa.
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
     * Determine whether the user can update the solicitação de isenção de taxa.
     *
     * @param  \App\Models\User                    $user
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return mixed
     */
    public function update(User $user, SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        return (Gate::allows('perfilusuario') && ($solicitacaoisencaotaxa->pessoas('Autor')->id == $user->id));    // permite que apenas o usuário autor da solicitação de isenção de taxa a edite
    }

    /**
     * Determine whether the user can update the solicitação de isenção de taxa status.
     *
     * @param  \App\Models\User                    $user
     * @param  \App\Models\SolicitacaoIsencaoTaxa  $solicitacaoisencaotaxa
     * @return mixed
     */
    public function updateStatus(User $user, SolicitacaoIsencaoTaxa $solicitacaoisencaotaxa)
    {
        return Gate::any(['perfiladmin', 'perfilgerente']);
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
