<?php

namespace App\Services;

use GuzzleHttp\Client;

class ViacepService
{
    protected $client;    // necessário devido à requisição HTTP do Guzzle

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function consultarCep(string $cep)
    {
        try {
            $response = $this->client->request('get', 'https://viacep.com.br/ws/' . $cep . '/json/', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('inscricoes-selecoes-pos.correios_api_token'),
                    'Content-Type' => 'application/json'
                ]
            ]);
            if ($response->getStatusCode() === 200)
                return json_decode($response->getBody(), true);
            else
                return ['error' => 'Erro inesperado ao consultar CEP no ViaCEP: ' . $response->getStatusCode()];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return ['error' => 'Erro ao consultar CEP no ViaCEP: ' . $e->getMessage()];
        } catch (\Exception $e) {
            return ['error' => 'Erro genérico ao consultar CEP no ViaCEP: ' . $e->getMessage()];
        }
    }
}
