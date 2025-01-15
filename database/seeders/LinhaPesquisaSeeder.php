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
        $programa_id_NEUROCIENCIAS_E_COMPORTAMENTO = Programa::where('nome', 'Neurociências e Comportamento (NEC)')->first()->id;
        $programa_id_PSICOLOGIA_ESCOLAR_E_DO_DESENVOLVIMENTO_HUMANO = Programa::where('nome', 'Psicologia Escolar e do Desenvolvimento Humano (PSA)')->first()->id;
        $programa_id_PSICOLOGIA_CLINICA = Programa::where('nome', 'Psicologia Clínica (PSC)')->first()->id;
        $programa_id_PSICOLOGIA_EXPERIMENTAL = Programa::where('nome', 'Psicologia Experimental (PSE)')->first()->id;
        $programa_id_PSICOLOGIA_SOCIAL = Programa::where('nome', 'Psicologia Social (PST)')->first()->id;

        $linhaspesquisa = [
            [
                'nome' => 'Sensação, Percepção e Movimento',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_NEUROCIENCIAS_E_COMPORTAMENTO,
            ],
            [
                'nome' => 'Neurotransmissores e Comportamento',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_NEUROCIENCIAS_E_COMPORTAMENTO,
            ],
            [
                'nome' => 'Desenvolvimento e Plasticidade',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_NEUROCIENCIAS_E_COMPORTAMENTO,
            ],
            [
                'nome' => 'História, Filosofia e Educação em Neurociências',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_NEUROCIENCIAS_E_COMPORTAMENTO,
            ],
            [
                'nome' => 'Desenvolvimento Humano e Aprendizagem',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_ESCOLAR_E_DO_DESENVOLVIMENTO_HUMANO,
            ],
            [
                'nome' => 'Desenvolvimento Humano e Avaliação Psicológica',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_ESCOLAR_E_DO_DESENVOLVIMENTO_HUMANO,
            ],
            [
                'nome' => 'Psicologia, Instituições e Sociedade: Mediações do Desenvolvimento Humano',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_ESCOLAR_E_DO_DESENVOLVIMENTO_HUMANO,
            ],
            [
                'nome' => 'Psicanálise Política: Cultura e Desenvolvimento Humano',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_ESCOLAR_E_DO_DESENVOLVIMENTO_HUMANO,
            ],
            [
                'nome' => 'Psicologia Escolar, Educação e Políticas Públicas',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_ESCOLAR_E_DO_DESENVOLVIMENTO_HUMANO,
            ],
            [
                'nome' => 'Psicanálise, Sofrimento e Política',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_CLINICA,
            ],
            [
                'nome' => 'Psicanálise, Intersubjetividade e Configurações Vinculares',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_CLINICA,
            ],
            [
                'nome' => 'Intervenções Clínicas em Saúde Mental: Diagnóstico, Ação Terapêutica e Prevenção',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_CLINICA,
            ],
            [
                'nome' => 'Análise do Comportamento',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_EXPERIMENTAL,
            ],
            [
                'nome' => 'Comportamento Animal e Etologia Humana',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_EXPERIMENTAL,
            ],
            [
                'nome' => 'Sensação, Perceção e Cognição',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_EXPERIMENTAL,
            ],
            [
                'nome' => 'Problemas Teóricos e Metodológicos',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_EXPERIMENTAL,
            ],
            [
                'nome' => 'Processos e Práticas Psicossociais: Direitos Humanos, Desigualdades e Política',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_SOCIAL,
            ],
            [
                'nome' => 'Processos e Práticas Psicossociais: Cultura e Subjetividade',
                'codpes_orientador' => 1543022,
                'programa_id' => $programa_id_PSICOLOGIA_SOCIAL,
            ],
        ];

        // adiciona registros na tabela linhaspesquisa
        foreach ($linhaspesquisa as $linhapesquisa)
            LinhaPesquisa::create($linhapesquisa);

        // adiciona registros na tabela linhapesquisa_selecao
        $selecao_SELECAO2025 = Selecao::where('nome', 'Seleção 2025')->first();
        $linhapesquisa_id_SENSACAO_PERCEPCAO_E_MOVIMENTO = LinhaPesquisa::where('nome', 'Sensação, Percepção e Movimento')->first()->id;
        $selecao_SELECAO2025->linhaspesquisa()->attach($linhapesquisa_id_SENSACAO_PERCEPCAO_E_MOVIMENTO);
    }
}
