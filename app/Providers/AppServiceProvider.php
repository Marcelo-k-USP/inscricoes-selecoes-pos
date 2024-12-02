<?php

namespace App\Providers;

use App\Services\CorreiosService;
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
        $this->app->singleton(CorreiosService::class, function ($app) {
            return new CorreiosService(new Client());
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
