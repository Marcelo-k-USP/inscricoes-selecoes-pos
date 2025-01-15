<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Seeder;

class ProgramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programas = [
            [
                'nome' => 'Neurociências e Comportamento (NEC)',
                'descricao' => 'Programa de Pós-Graduação em Neurociências e Comportamento'
            ],
            [
                'nome' => 'Psicologia Escolar e do Desenvolvimento Humano (PSA)',
                'descricao' => 'Programa de Pós-Graduação em Psicologia Escolar e do Desenvolvimento Humano'
            ],
            [
                'nome' => 'Psicologia Clínica (PSC)',
                'descricao' => 'Programa de Pós-Graduação em Psicologia Clínica'
            ],
            [
                'nome' => 'Psicologia Experimental (PSE)',
                'descricao' => 'Programa de Pós-Graduação em Psicologia Experimental'
            ],
            [
                'nome' => 'Psicologia Social (PST)',
                'descricao' => 'Programa de Pós-Graduação em Psicologia Social'
            ],
        ];

        // adiciona registros na tabela programas
        foreach ($programas as $programa)
            Programa::create($programa);
    }
}
