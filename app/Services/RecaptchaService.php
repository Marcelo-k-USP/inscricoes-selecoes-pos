<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function revalidate(string $recaptcha_response)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('inscricoes-selecoes-pos.recaptcha_secret_key'),
            'response' => $recaptcha_response,
            'remoteip' => request()->ip(),
        ]);
        return $response->json()['success'];
    }
}
