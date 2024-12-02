<?php

namespace App\Services;

use GuzzleHttp\Client;

class CorreiosService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function consultarCep($cep)
    {
        try {
            $response = $this->client->request('get', 'https://webservice.correios.com.br/service/rest/cep/' . $cep, [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('selecoes-pos.correios_api_token'),
                    'Content-Type' => 'application/json'
                ]
            ]);
            if ($response->getStatusCode() === 200)
                return json_decode($response->getBody(), true);
            else
                return ['error' => 'Erro inesperado ao consultar CEP nos Correios: ' . $response->getStatusCode()];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return ['error' => 'Erro ao consultar CEP nos Correios: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['error' => 'Erro genérico ao consultar CEP nos Correios: ' . $e->getMessage()];
        }
    }
}
