<?php

namespace Database\Seeders;

use App\Models\Parametro;
use Illuminate\Database\Seeder;

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
                'boleto_valor' => 50.0,
                'boleto_codigo_fonte_recurso' => 514,
                'boleto_estrutura_hierarquica' => '\DIR\ATAC-47\SVPOSGR-47',
            ],
        ];

        // adiciona registros na tabela parâmetros
        foreach ($parametros as $parametro)
            Parametro::create($parametro);
    }
}
