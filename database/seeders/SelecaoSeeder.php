<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Categoria;
use \App\Models\Programa;
use \App\Models\Selecao;
use \App\Models\User;
use Carbon\Carbon;

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
        $programa_id_PROGRAMA1 = Programa::where('nome', 'Programa 1')->first()->id;

        $selecoes = [
            [
                'nome' => 'Seleção 2025',
                'estado' => 'Em andamento',
                'descricao' => 'Processo Seletivo 2025 Aluno Regular',
                'data_inicio' => Carbon::createFromFormat('d/m/Y', '01/12/2024')->format('Y-m-d'),
                'data_fim' => Carbon::createFromFormat('d/m/Y', '01/01/2025')->format('Y-m-d'),
                'template' => '{
                    "nome": {
                        "label": "Nome",
                        "type": "text",
                        "required": true,
                        "index": 0
                    }
                }',
                'categoria_id' => $categoria_id_ALUNOREGULAR,
                'programa_id' => $programa_id_PROGRAMA1,
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
