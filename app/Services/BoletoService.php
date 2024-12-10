<?php

namespace App\Services;

use GuzzleHttp\Client;

class BoletoService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function gerarBoleto()
    {
        return ;
    }
}
