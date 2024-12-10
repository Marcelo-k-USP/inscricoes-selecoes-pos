<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Parametro;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parametros = [
            [
                'boleto_valor' => 50,
            ],
        ];

        // adiciona registros na tabela parâmetros
        foreach ($parametros as $parametro)
            Parametro::create($parametro);
    }
}
