<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MotivoIsencaoTaxa;
use App\Models\Selecao;

class MotivoIsencaoTaxaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $selecao_SELECAO2025ALUNOREGULAR = Selecao::where('nome', 'Seleção 2025 Aluno Regular')->first();

        $motivosisencaotaxa = [
            [
                'nome' => 'Aluno de Pós em Universidade Pública Brasileira',
            ],
            [
                'nome' => 'Aluno de Instituição Estrangeira com Convênio com o ' . strtoupper(config('laravel-usp-theme.skin')) . '-USP',
            ],
            [
                'nome' => 'Servidor(a) da USP',
            ],
            [
                'nome' => 'Pessoa em Situação de Dificuldade Socioeconômica',
            ],
            [
                'nome' => 'Pessoa Preta, Parda ou Indígena',
            ],
            [
                'nome' => 'Pessoa com Deficiência',
            ],
            [
                'nome' => 'Pessoa Transexual, Travesti ou Não-Binarie',
            ],
            [
                'nome' => 'Pessoa de Povo ou Comunidade Tradicional',
            ],
            [
                'nome' => 'Pessoa Portadora de Visto Humanitário',
            ],
        ];

        foreach ($motivosisencaotaxa as $motivoisencaotaxa) {
            // adiciona registro na tabela motivosisencaotaxa
            $motivoisencaotaxa = MotivoIsencaoTaxa::create($motivoisencaotaxa);

            // adiciona registro na tabela motivosisencaotaxa_selecao
            $selecao_SELECAO2025ALUNOREGULAR->motivosisencaotaxa()->attach($motivoisencaotaxa->id);
        }
    }
}
