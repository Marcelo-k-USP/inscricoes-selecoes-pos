<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Selecao;
use \App\Models\Processo;
use \App\Models\User;

class SelecaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $processo_id_POSALUNOREGULAR = Processo::where('nome', 'Pós Aluno Regular')->first()->id;
        
        $selecoes = [
            [
                'nome' => 'teste 1',
                'descricao' => 'Processo Seletivo 1',
                'processo_id' => $processo_id_POSALUNOREGULAR
            ]
        ];
 
        // adiciona registros na tabela selecoes
        foreach ($selecoes as $selecao)
            Selecao::create($selecao);
    }
}
