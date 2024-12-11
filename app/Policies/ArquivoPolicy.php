<?php

namespace App\Policies;

use App\Models\Arquivo;
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
     * @param  \App\Models\?User    $user
     * @param  \App\Models\Arquivo  $arquivo
     * @param  string               $classe_nome
     * @return mixed
     */
    public function view(?User $user, Arquivo $arquivo, string $classe_nome)    // se não colocarmos a interrogação, esta policy não é invocada no caso de usuário não logado
    {
        if ($classe_nome == 'Selecao')
            return true;                                           // permite que todos baixem arquivos de seleções

        if (Gate::allows('perfilusuario'))
            foreach ($arquivo->inscricoes as $inscricao) {
                $autor_inscricao = $inscricao->pessoas('Autor');
                if ($autor_inscricao && ($autor_inscricao->id == $user->id))
                    return true;                                   // permite que usuários baixem arquivos de suas inscrições
            }

        return Gate::any(['perfiladmin', 'perfilgerente']);        // permite que admins e gerentes baixem todos os arquivos
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User                  $user
     * @param  \App\Models\Selecao ou Inscricao  $objeto
     * @param  string                            $classe_nome
     * @return mixed
     */
    public function create(User $user, $objeto, string $classe_nome)
    {
        if ($classe_nome == 'Selecao')
            return Gate::any(['perfiladmin', 'perfilgerente']);    // permite que admins e gerentes subam arquivos de seleção

        if (Gate::allows('perfilusuario')) {
            $autor_inscricao = $objeto->pessoas('Autor');
            if ($autor_inscricao && ($autor_inscricao->id == $user->id))
                return true;                                       // permite que usuários subam arquivos em sua inscrição
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User                  $user
     * @param  \App\Models\Arquivo               $arquivo
     * @param  \App\Models\Selecao ou Inscricao  $objeto
     * @param  string                            $classe_nome
     * @return mixed
     */
    public function update(User $user, Arquivo $arquivo, $objeto, string $classe_nome)
    {
        return $this->authorize_update_delete($user, $arquivo, $objeto, $classe_nome);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User                  $user
     * @param  \App\Models\Arquivo               $arquivo
     * @param  \App\Models\Selecao ou Inscricao  $objeto
     * @param  string                            $classe_nome
     * @return mixed
     */
    public function delete(User $user, Arquivo $arquivo, $objeto, string $classe_nome)
    {
        return $this->authorize_update_delete($user, $arquivo, $objeto, $classe_nome);
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

    private function authorize_update_delete(User $user, Arquivo $arquivo, $objeto, string $classe_nome)
    {
        if ($classe_nome == 'Selecao')
            return Gate::any(['perfiladmin', 'perfilgerente']);    // permite que admins e gerentes renomeiem/apaguem arquivos de seleção

        if (Gate::allows('perfilusuario')) {
            $autor_arquivo_id = $arquivo->user_id;
            $autor_inscricao = $objeto->pessoas('Autor');
            if (($autor_arquivo_id == $user->id) && $autor_inscricao && ($autor_inscricao->id == $user->id))
                return true;                                       // permite que usuários renomeiem/apaguem arquivos em sua inscrição
        }
    }
}
