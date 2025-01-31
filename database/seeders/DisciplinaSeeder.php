<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disciplina;
use App\Models\Selecao;

class DisciplinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $disciplinas = [
            [
                'sigla' => 'NEC5779',
                'nome' => 'Métodos de Pesquisa em Psicofisiologia Sensorial II',
            ],
            [
                'sigla' => 'NEC5785',
                'nome' => 'Introdução à Farmacologia Comportamental',
            ],
            [
                'sigla' => 'NEC5762',
                'nome' => 'Avanços em Psicologia Sensorial da Visão',
            ],
            [
                'sigla' => 'NEC5777',
                'nome' => 'Imagem corporal e transtornos alimentares',
            ],
            [
                'sigla' => 'NEC5725',
                'nome' => 'Controle Motor Humano: Uma Visão Comportamental',
            ],
            [
                'sigla' => 'NEC5784',
                'nome' => 'Biologia do Sono: aspectos básicos e ecologia humana',
            ],
            [
                'sigla' => 'NEC5781',
                'nome' => 'Neurociência e Didática da Matemática',
            ],
            [
                'sigla' => 'NEC5774',
                'nome' => 'Neuropsicologia na Saúde Mental: da Avaliação a Reabilitação',
            ],
            [
                'sigla' => 'PSE5972',
                'nome' => 'Da Comunicação a Divulgação da Ciência',
            ],
            [
                'sigla' => 'PSE5998',
                'nome' => 'Journal Club: Evolução e Ontogenia das Narrativas - intuições e emoções morais',
            ],
            [
                'sigla' => 'PSE5924',
                'nome' => 'Medidas Quantitativas em Psicologia e o Método Psicofísico',
            ],
            [
                'sigla' => 'PSE5770',
                'nome' => 'Etologia',
            ],
            [
                'sigla' => 'PSE5984',
                'nome' => 'Jornal Club: Discussão de Artigos e Projetos de Pesquisa em Psicologia Evolucionista',
            ],
            [
                'sigla' => 'PSE5999',
                'nome' => 'Nutrição e saúde mental',
            ],
            [
                'sigla' => 'PSA5847',
                'nome' => 'Prática Psicológica em Instituições e Psicologia Social Clínica: Problemas Epistemológicos, Metodológicos e Práticos',
            ],
            [
                'sigla' => 'PSA5982',
                'nome' => 'Seminário Avançado de Pesquisa em Psicologia do Desenvolvimento Moral IV',
            ],
            [
                'sigla' => 'PSA5913',
                'nome' => 'Moralidade: Normatividade, Desenvolvimento e Domínios Sociais',
            ],
            [
                'sigla' => 'PSA5971',
                'nome' => 'Seminário Avançado de Pesquisa em Violência e Formação no Mundo Administrado sob a Perspectiva da Teoria Crítica I',
            ],
            [
                'sigla' => 'PSA6031',
                'nome' => 'Psicanálise & Desenvolvimento X. O desenvolvimento do cérebro e a criação da subjetividade humana',
            ],
            [
                'sigla' => 'PSA6033',
                'nome' => 'Lembrar, esquecer, desesquecer: memória, memoriais e o futuro das democracias',
            ],
            [
                'sigla' => 'PSA5939',
                'nome' => 'Seminário Avançado de Pesquisa em Psicologia Cognitiva e Linguagem I',
            ],
            [
                'sigla' => 'PSA6029',
                'nome' => 'Psicologia Psicanalítica Concreta e Psicanalise Relacional Winnicottiana no Estudo de Sofrimentos Sociais de Mulheres-mães',
            ],
            [
                'sigla' => 'PSA5929',
                'nome' => 'Análise Institucional do Discurso como Método de Pesquisa em Psicologia',
            ],
            [
                'sigla' => 'PSA5989',
                'nome' => 'Seminários Avançados de Pesquisa I: Prevenção em Educação e Saúde',
            ],
            [
                'sigla' => 'PSA5970',
                'nome' => 'Sistematização Crítica da Abordagem Histórica Cultural e Possíveis Aplicações na Psicologia',
            ],
            [
                'sigla' => 'PST5986',
                'nome' => 'Sexualidade e Gênero: Perspectivas Teóricas e Recortes Empíricos Contemporâneos',
            ],
            [
                'sigla' => 'PST5716',
                'nome' => 'O Homem e o Trabalho na Administração Tradicional e na Emergente',
            ],
            [
                'sigla' => 'PST5827',
                'nome' => 'Trabalhar na Contemporaneidade: Identidade, Carreira e Futuro',
            ],
            [
                'sigla' => 'PST5977',
                'nome' => 'Oficinas de Escrita de Artigos Científicos em Psicologia Social I: A Estrutura e a organização do Manuscrito',
            ],
            [
                'sigla' => 'PST5978',
                'nome' => 'Uma História Oculta(da) da Psicologia: Impactos da Constituição do Inconsciente na Psicologia Social',
            ],
            [
                'sigla' => 'PST5981',
                'nome' => 'Psicologia Social e Processos Digitais',
            ],
            [
                'sigla' => 'PST5917',
                'nome' => 'Seminário Avançado de Pesquisa em Psicologia Social e Políticas Públicas II',
            ],
            [
                'sigla' => 'PST5941',
                'nome' => 'Intersecções entre psicanálise e Psicologia Social: a Marcação Social da Diferença',
            ],
            [
                'sigla' => 'PST5980',
                'nome' => 'A Produção e a Mobilização Social de Sintomas: Diagnósticos e Possibilidades de Transformação',
            ],
            [
                'sigla' => 'PST5922',
                'nome' => 'Ethos Humano e Mundo Contemporâneo: Lições Preliminares',
            ],
            [
                'sigla' => 'PST5968',
                'nome' => 'Seminários de Elaboração de Projetos de Pesquisa em Psicologia Social',
            ],
            [
                'sigla' => 'PST5969',
                'nome' => 'Fundamentos de Psicanálise e Direito',
            ],
            [
                'sigla' => 'PST5982',
                'nome' => 'Instituições, Modos de Subjetivação e Produção do Comum',
            ],
            [
                'sigla' => 'PST5985',
                'nome' => 'Loucura e Subalternidade: Lacan e Le Guillant Sobre o Caso das Irmãs Papin',
            ],
            [
                'sigla' => 'PSC6073',
                'nome' => 'Clínica Psicanalítica: Novas Perspectivas na Atualidade',
            ],
            [
                'sigla' => 'PSC6088',
                'nome' => 'Psicoterapia Comportamental e Contextual para Insônia',
            ],
            [
                'sigla' => 'PSC6050',
                'nome' => 'Seminários Avançados de Pesquisa I: Prevenção em Educação e Saúde',
            ],
            [
                'sigla' => 'PSC6058',
                'nome' => 'Seminários Avançados de Pesquisa II: Prevenção em Educação e Saúde',
            ],
            [
                'sigla' => 'PSC6102',
                'nome' => 'Clínicas Psicanalíticas no Social e do Social: O Real e a Política nos Dispositivos, na Transmissão e na Criação de Conceitos em Psicanálise a Partir de Experiências Segregação e Exclusão',
            ],
            [
                'sigla' => 'PSC6036',
                'nome' => 'Abordagem Psicanalítica do Sofrimento nas Instituições de Saúde',
            ],
        ];

        // adiciona registros na tabela disciplinas
        foreach ($disciplinas as $disciplina)
            Disciplina::create($disciplina);

        // adiciona registros na tabela selecao_disciplina
        $selecao_id_SELECAO2025ALUNOESPECIAL = Selecao::where('nome', 'Seleção 2025 Aluno Especial')->first()->id;
        foreach (Disciplina::all() as $disciplina)
            $disciplina->selecoes()->attach($selecao_id_SELECAO2025ALUNOESPECIAL);
    }
}
