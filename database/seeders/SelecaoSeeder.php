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
        $categoria_id_ALUNOREGULAR = Categoria::where('nome', 'Aluno Regular')->first()->id;
        
        $selecoes = [
            [
                'nome' => 'Seleção 2025',
                'estado' => 'Em andamento',
                'descricao' => 'Processo Seletivo 2025 Aluno Regular',
                'categoria_id' => $categoria_id_ALUNOREGULAR,
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
