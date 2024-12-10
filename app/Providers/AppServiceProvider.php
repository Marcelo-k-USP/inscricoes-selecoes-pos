<?php

namespace App\Providers;

use App\Models\Inscricao;
use App\Observers\InscricaoObserver;
use App\Services\BoletoService;
use App\Services\RecaptchaService;
use App\Services\ViacepService;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // registra os services
        $this->app->singleton(BoletoService::class, function ($app) {
            return new BoletoService();
        });
        $this->app->singleton(RecaptchaService::class, function ($app) {
            return new RecaptchaService();
        });
        $this->app->singleton(ViacepService::class, function ($app) {
            return new ViacepService(new Client());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if(config('selecoes-pos.forcar_https'))
            \URL::forceScheme('https');

        // registra os observers
        Inscricao::observe(InscricaoObserver::class);
    }
}
