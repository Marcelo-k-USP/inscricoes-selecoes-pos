<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        Gate::define('gerente', function ($user) {
            return $user->is_admin;
        });

        Gate::define('usuario', function ($user) {
            return $user;
        });

        # perfis
        # o perfil é o modo como o usuário se apresenta
        # ideal para mostrar os menus e a lista de categorias
        Gate::define('perfiladmin', function ($user) {
            return (session('perfil') == 'admin');
        });

        Gate::define('perfilgerente', function ($user) {
            return (session('perfil') == 'gerente');
        });

        Gate::define('perfilusuario', function ($user) {
            return ((session('perfil') == 'usuario') || empty(session('perfil')));
        });

        Gate::define('trocarPerfil', function ($user) {
            return Gate::any(['admin', 'gerente']);
        });

        # se o admin assumir identidade de outro usuário, permite retornar
        Gate::define('desassumir', function ($user) {
            return session('adminCodpes');
        });

        # policies
        Gate::resource('admin', 'App\Policies\AdminPolicy');
        Gate::resource('arquivos', 'App\Policies\ArquivoPolicy');
        Gate::resource('categorias', 'App\Policies\CategoriaPolicy');
        Gate::resource('disciplinas', 'App\Policies\DisciplinaPolicy');
        Gate::resource('funcoes', 'App\Policies\FuncaoPolicy');
        Gate::resource('inscricoes', 'App\Policies\InscricaoPolicy');
        Gate::define('inscricoes.viewTheir', 'App\Policies\InscricaoPolicy@viewTheir');    // Gate::resource só define policies padrão (viewAny, view, create, etc.)... portanto, para policies fora do padrão (como viewTheir), precisamos explicitamente criar os apontamentos para elas
        Gate::define('inscricoes.updateStatus', 'App\Policies\InscricaoPolicy@updateStatus');
        Gate::resource('linhaspesquisa', 'App\Policies\LinhaPesquisaPolicy');
        Gate::resource('localusers', 'App\Policies\LocalUserPolicy');
        Gate::resource('motivosisencaotaxa', 'App\Policies\MotivoIsencaoTaxaPolicy');
        Gate::resource('parametros', 'App\Policies\ParametroPolicy');
        Gate::resource('programas', 'App\Policies\ProgramaPolicy');
        Gate::resource('selecoes', 'App\Policies\SelecaoPolicy');
        Gate::resource('solicitacoesisencaotaxa', 'App\Policies\SolicitacaoIsencaoTaxaPolicy');
        Gate::define('solicitacoesisencaotaxa.viewTheir', 'App\Policies\SolicitacaoIsencaoTaxaPolicy@viewTheir');
        Gate::define('solicitacoesisencaotaxa.updateStatus', 'App\Policies\SolicitacaoIsencaoTaxaPolicy@updateStatus');
        Gate::resource('tiposarquivo', 'App\Policies\TipoArquivoPolicy');
        Gate::resource('users', 'App\Policies\UserPolicy');
    }
}
