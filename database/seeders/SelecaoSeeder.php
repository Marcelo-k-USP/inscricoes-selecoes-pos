<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Selecao;
use \App\Models\Categoria;
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
        $categoria_id_POSALUNOREGULAR = Categoria::where('nome', 'Pós Aluno Regular')->first()->id;
        
        $selecoes = [
            [
                'nome' => 'teste 1',
                'estado' => 'Em andamento',
                'descricao' => 'Processo Seletivo 1',
                'categoria_id' => $categoria_id_POSALUNOREGULAR,
                'settings' => '{
                    "instrucoes": "Preencher ..."
                }'
            ]
        ];
 
        // adiciona registros na tabela selecoes
        foreach ($selecoes as $selecao)
            Selecao::create($selecao);
    }
}
