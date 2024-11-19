<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LinhaPesquisa;
use App\Models\Selecao;

class LinhasPesquisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $linhaspesquisa = [
            [
                'nome' => 'Desenvolvimento e Aprendizagem',
                'codpes_docente' => 1543022,
            ],
            [
                'nome' => 'Psicologia, Instituições e Sociedade: Mediações do Desenvolvimento Humano',
                'codpes_docente' => 1543022,
            ],
            [
                'nome' => 'Psicologia Escolar, Educação e Políticas Públicas',
                'codpes_docente' => 1543022,
            ],
            [
                'nome' => 'Psicanálise e Política: Cultura e Desenvolvimento Humano',
                'codpes_docente' => 1543022,
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
