<?php

namespace App\Providers;

use App\Models\Inscricao;
use App\Services\BoletoService;
use App\Services\RecaptchaService;
use App\Services\ViacepService;
use GuzzleHttp\Client;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
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

        if(config('inscricoes-selecoes-pos.forcar_https'))
            \URL::forceScheme('https');

        if (config('mail.copiarRemetente'))
            // faz com que todo e qualquer e-mail enviado para os diversos atores seja copiado para o e-mail de envio do sistema
            // desta forma, na caixa de entrada do e-mail de envio do sistema, teremos um histórico de todos os e-mails enviados
            Event::listen(MessageSending::class, function (MessageSending $event) {
                $event->message->addBcc(config('mail.from.address'));
            });
    }
}
