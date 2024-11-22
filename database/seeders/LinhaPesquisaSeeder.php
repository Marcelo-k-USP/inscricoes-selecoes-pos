<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LinhaPesquisa;
use App\Models\Programa;
use App\Models\Selecao;

class LinhaPesquisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programa_id_PROGRAMA1 = Programa::where('nome', 'Programa 1')->first()->id;
        $programa_id_PROGRAMA2 = Programa::where('nome', 'Programa 2')->first()->id;
        
        $linhaspesquisa = [
            [
                'nome' => 'Desenvolvimento e Aprendizagem',
                'codpes_docente' => 1543022,
                'programa_id' => $programa_id_PROGRAMA1,
            ],
            [
                'nome' => 'Psicologia, Instituições e Sociedade: Mediações do Desenvolvimento Humano',
                'codpes_docente' => 1543022,
                'programa_id' => $programa_id_PROGRAMA1,
            ],
            [
                'nome' => 'Psicologia Escolar, Educação e Políticas Públicas',
                'codpes_docente' => 1543022,
                'programa_id' => $programa_id_PROGRAMA1,
            ],
            [
                'nome' => 'Psicanálise e Política: Cultura e Desenvolvimento Humano',
                'codpes_docente' => 1543022,
                'programa_id' => $programa_id_PROGRAMA2,
            ],
        ];
 
        // adiciona registros na tabela linhaspesquisa
        foreach ($linhaspesquisa as $linhapesquisa)
            LinhaPesquisa::create($linhapesquisa);

        // adiciona registros na tabela linhapesquisa_selecao
        $selecao_SELECAO2025 = Selecao::where('nome', 'Seleção 2025')->first();
        $linhapesquisa_id_DESENVOLVIMENTOEAPRENDIZAGEM = LinhaPesquisa::where('nome', 'Desenvolvimento e Aprendizagem')->first()->id;
        $selecao_SELECAO2025->linhaspesquisa()->attach($linhapesquisa_id_DESENVOLVIMENTOEAPRENDIZAGEM);
    }
}
