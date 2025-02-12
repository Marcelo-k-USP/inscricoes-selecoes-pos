<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LinhaPesquisa;
use App\Models\Nivel;
use App\Models\NivelLinhaPesquisa;
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
        $programa_id_NEUROCIENCIASECOMPORTAMENTONEC = Programa::where('nome', 'Neurociências e Comportamento (NEC)')->first()->id;
        $programa_id_PSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA = Programa::where('nome', 'Psicologia Escolar e do Desenvolvimento Humano (PSA)')->first()->id;
        $programa_id_PSICOLOGIACLINICAPSC = Programa::where('nome', 'Psicologia Clínica (PSC)')->first()->id;
        $programa_id_PSICOLOGIAEXPERIMENTALPSE = Programa::where('nome', 'Psicologia Experimental (PSE)')->first()->id;
        $programa_id_PSICOLOGIASOCIALPST = Programa::where('nome', 'Psicologia Social (PST)')->first()->id;

        $linhaspesquisa = [
            [
                'nome' => 'Sensação, Percepção e Movimento',
                'programa_id' => $programa_id_NEUROCIENCIASECOMPORTAMENTONEC,
            ],
            [
                'nome' => 'Neurotransmissores e Comportamento',
                'programa_id' => $programa_id_NEUROCIENCIASECOMPORTAMENTONEC,
            ],
            [
                'nome' => 'Desenvolvimento e Plasticidade',
                'programa_id' => $programa_id_NEUROCIENCIASECOMPORTAMENTONEC,
            ],
            [
                'nome' => 'História, Filosofia e Educação em Neurociências',
                'programa_id' => $programa_id_NEUROCIENCIASECOMPORTAMENTONEC,
            ],
            [
                'nome' => 'Desenvolvimento Humano e Aprendizagem',
                'programa_id' => $programa_id_PSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA,
            ],
            [
                'nome' => 'Desenvolvimento Humano e Avaliação Psicológica',
                'programa_id' => $programa_id_PSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA,
            ],
            [
                'nome' => 'Psicologia, Instituições e Sociedade: Mediações do Desenvolvimento Humano',
                'programa_id' => $programa_id_PSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA,
            ],
            [
                'nome' => 'Psicanálise e Política: Cultura e Desenvolvimento Humano',
                'programa_id' => $programa_id_PSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA,
            ],
            [
                'nome' => 'Psicologia Escolar, Educação e Políticas Públicas',
                'programa_id' => $programa_id_PSICOLOGIAESCOLAREDODESENVOLVIMENTOHUMANOPSA,
            ],
            [
                'nome' => 'Psicanálise, Sofrimento e Política',
                'programa_id' => $programa_id_PSICOLOGIACLINICAPSC,
            ],
            [
                'nome' => 'Psicanálise, Intersubjetividade e Configurações Vinculares',
                'programa_id' => $programa_id_PSICOLOGIACLINICAPSC,
            ],
            [
                'nome' => 'Intervenções Clínicas em Saúde Mental: Diagnóstico, Ação Terapêutica e Prevenção',
                'programa_id' => $programa_id_PSICOLOGIACLINICAPSC,
            ],
            [
                'nome' => 'Análise do Comportamento',
                'programa_id' => $programa_id_PSICOLOGIAEXPERIMENTALPSE,
            ],
            [
                'nome' => 'Comportamento Animal e Etologia Humana',
                'programa_id' => $programa_id_PSICOLOGIAEXPERIMENTALPSE,
            ],
            [
                'nome' => 'Sensação, Perceção e Cognição',
                'programa_id' => $programa_id_PSICOLOGIAEXPERIMENTALPSE,
            ],
            [
                'nome' => 'Problemas Teóricos e Metodológicos',
                'programa_id' => $programa_id_PSICOLOGIAEXPERIMENTALPSE,
            ],
            [
                'nome' => 'Processos e Práticas Psicossociais: Direitos Humanos, Desigualdades e Política',
                'programa_id' => $programa_id_PSICOLOGIASOCIALPST,
            ],
            [
                'nome' => 'Processos e Práticas Psicossociais: Cultura e Subjetividade',
                'programa_id' => $programa_id_PSICOLOGIASOCIALPST,
            ],
        ];

        // adiciona registros na tabela linhaspesquisa
        foreach ($linhaspesquisa as $linhapesquisa)
            LinhaPesquisa::create($linhapesquisa);

        // adiciona registros na tabela nivel_linhapesquisa
        foreach (LinhaPesquisa::all() as $linhapesquisa)
            foreach (Nivel::all() as $nivel)
                $linhapesquisa->niveis()->attach($nivel->id);

        $selecao_SELECAO2025ALUNOREGULARNEC = Selecao::where('nome', 'Seleção 2025 Aluno Regular NEC')->first();

        // adiciona registros na tabela selecao_nivellinhapesquisa
        $nivellinhapesquisa_id_MESTRADOSENSACAOPERCEPCAOEMOVIMENTO = NivelLinhaPesquisa::whereHas('nivel', function ($query) { $query->where('nome', 'Mestrado'); })->whereHas('linhapesquisa', function ($query) { $query->where('nome', 'Sensação, Percepção e Movimento'); })->first()->id;
        $selecao_SELECAO2025ALUNOREGULARNEC->niveislinhaspesquisa()->attach($nivellinhapesquisa_id_MESTRADOSENSACAOPERCEPCAOEMOVIMENTO);
    }
}
