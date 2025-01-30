<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FuncaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $funcoes = [
            [
                'codpes' => 5098371,    // Moisés do Nascimento Soares
                'programa' => 'Neurociências e Comportamento (NEC)',
                'funcao' => 'Secretários(as) do Programa',
            ],
            [
                'codpes' => 2806023,    // Fernanda Leite Paiva
                'programa' => 'Psicologia Escolar e do Desenvolvimento Humano (PSA)',
                'funcao' => 'Secretários(as) do Programa',
            ],
            [
                'codpes' => 2503151,    // Cláudia Lima Rodrigues da Rocha
                'programa' => 'Psicologia Clínica (PSC)',
                'funcao' => 'Secretários(as) do Programa',
            ],
            [
                'codpes' => 2438068,    // Fátima Tereza Gonçalves
                'programa' => 'Psicologia Experimental (PSE)',
                'funcao' => 'Secretários(as) do Programa',
            ],
            [
                'codpes' => 2487800,    // Teresa Cristina de Oliveira Peres
                'programa' => 'Psicologia Social (PST)',
                'funcao' => 'Secretários(as) do Programa',
            ],
            [
                'codpes' => 3257875,    // Marcelo Fernandes da Costa
                'programa' => 'Neurociências e Comportamento (NEC)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 5032360,    // Daniela Maria Oliveira Bonci
                'programa' => 'Neurociências e Comportamento (NEC)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 4864151,    // Luciana Maria Caetano
                'programa' => 'Psicologia Escolar e do Desenvolvimento Humano (PSA)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' =>  907384,    // Leopoldo Pereira Fulgencio Junior
                'programa' => 'Psicologia Escolar e do Desenvolvimento Humano (PSA)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 2103422,    // Christian Ingo Lenz Dunker
                'programa' => 'Psicologia Clínica (PSC)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 5593721,    // Andrés Eduardo Aguirre Antúnez
                'programa' => 'Psicologia Clínica (PSC)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' =>  576462,    // Briseida Dogo de Resende
                'programa' => 'Psicologia Experimental (PSE)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 7811859,    // Jaroslava Varella Valentova
                'programa' => 'Psicologia Experimental (PSE)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 1138617,    // Fabio de Oliveira
                'programa' => 'Psicologia Social (PST)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 1502231,    // Maria Cristina Gonçalves Vicentin
                'programa' => 'Psicologia Social (PST)',
                'funcao' => 'Coordenadores do Programa',
            ],
            [
                'codpes' => 7190868,    // Carina Müller Sasse
                'funcao' => 'Serviço de Pós-Graduação',
            ],
            [
                'codpes' => 2789780,    // Ronaldo Correa de Assis
                'funcao' => 'Serviço de Pós-Graduação',
            ],
            [
                'codpes' => 3656230,    // Joana Darc de Lima Barbosa
                'funcao' => 'Serviço de Pós-Graduação',
            ],
            [
                'codpes' => 5032360,    // Daniela Maria Oliveira Bonci
                'funcao' => 'Coordenadores da Pós-Graduação',
            ],
            [
                'codpes' => 4864151,    // Luciana Maria Caetano
                'funcao' => 'Coordenadores da Pós-Graduação',
            ],
        ];

        // adiciona registros na tabela user_programa
        foreach ($funcoes as $funcao) {
            $user = User::findOrCreateFromReplicado($funcao['codpes']);
            if ($user)
                $user->associarProgramaFuncao($funcao['programa'] ?? null, $funcao['funcao']);
        }
    }
}
