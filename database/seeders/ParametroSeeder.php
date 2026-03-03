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
                'boleto_codigo_fonte_recurso' => 514,
                'boleto_estrutura_hierarquica' => '\DIR\ATAC-47\SVPOSGR-47',
                'link_acompanhamento_especiais' => 'https://www.ip.usp.br/site/alunos-especiais-3/',
                'email_servicoposgraduacao' => 'inscricao_pos_ip@usp.br',
                'email_secaoinformatica' => 'inforip@usp.br',
                'email_gerenciamentosite' => 'website_ip@usp.br',
            ],
        ];

        // adiciona registros na tabela parâmetros
        foreach ($parametros as $parametro)
            Parametro::create($parametro);
    }
}
