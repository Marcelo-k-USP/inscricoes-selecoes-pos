<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Processo;

class ProcessoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $processos = [
            [
                'nome' => 'Pós Aluno Regular',
                'descricao' => 'Mestrado e Doutorado'
            ],
            [
                'nome' => 'Pós Aluno Especial',
                'descricao' => 'Em disciplinas'
            ],
            [
                'nome' => 'Pós Aluno Interunidades',
                'descricao' => 'Prolam'
            ]
        ];

        // adiciona registros na tabela processos
        foreach ($processos as $processo)
            Processo::create($processo);
    }
}
