<?php

namespace App\Providers;

use App\Services\ViacepService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
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
        if(config('selecoes-pos.forcar_https')) {
            \URL::forceScheme('https');
        }
    }
}
