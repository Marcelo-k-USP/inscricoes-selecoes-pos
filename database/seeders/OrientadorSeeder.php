<?php

namespace Database\Seeders;

use App\Models\LinhaPesquisa;
use App\Models\Orientador;
use Illuminate\Database\Seeder;

class OrientadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orientadores = [
            ['codpes' =>   '647530'],    // Adriana Marcondes Machado
            ['codpes' =>  '5593721'],    // Andrés Eduardo Aguirre Antúnez
            ['codpes' =>   '339731'],    // Antonio de Padua Serafim
            ['codpes' =>  '3386535'],    // Avelino Luiz Rodrigues
            ['codpes' =>   '576462'],    // Briseida Dogo de Resende
            ['codpes' =>  '2103422'],    // Christian Ingo Lenz Dunker
            ['codpes' =>  '5710209'],    // Cláudia Kami Bastos Oshiro
            ['codpes' =>  '5811484'],    // Daniel Kupermann
            ['codpes' =>  '3463419'],    // Danilo Silva Guimarães
            ['codpes' =>    '94249'],    // Eduardo Benedicto Ottoni
            ['codpes' =>    '77048'],    // Emma Otta
            ['codpes' =>    '96853'],    // Francisco Assumpção Júnior
            ['codpes' =>  '3001174'],    // Fraulein V. de Paula
            ['codpes' => '10229702'],    // Gabriel Inticher Binkowski
            ['codpes' =>   '821761'],    // Gerson Yukio Tomanari
            ['codpes' =>  '2088882'],    // Gilberto Safra
            ['codpes' =>   '791212'],    // Helena R. Rosa
            ['codpes' =>   '962810'],    // Isabel Cristina Gomes
            ['codpes' =>    '90061'],    // Ivonise Fernandes da Motta
            ['codpes' =>  '7811859'],    // Jaroslava Valentova
            ['codpes' =>   '774870'],    // Leila Cury Tardivo
            ['codpes' =>   '907384'],    // Leopoldo Fulgencio
            ['codpes' =>  '4864151'],    // Luciana Maria Caetano
            ['codpes' =>  '3257875'],    // Marcelo Fernandes da Costa
            ['codpes' =>  '3333504'],    // Marcelo Frota Benvenuti
            ['codpes' =>  '2163232'],    // Marcia Helena da Silva Melo
            ['codpes' =>  '2085249'],    // Maria Isabel da Silva Leme
            ['codpes' =>  '1489943'],    // Maria Martha Costa Hübner
            ['codpes' =>  '7915462'],    // Marina Ferreira da Rosa Ribeiro
            ['codpes' =>  '3648589'],    // Mirella Gualtieri
            ['codpes' =>  '2557349'],    // Miriam Garcia Mijares
            ['codpes' =>  '2142872'],    // Nelson Ernesto Coelho Júnior
            ['codpes' =>  '8314620'],    // Nicolas Chaline
            ['codpes' =>  '1637670'],    // Pablo de Carvalho Godoy Castanho
            ['codpes' =>  '1505248'],    // Patrícia Izar
            ['codpes' =>  '2352751'],    // Paula Debert
            ['codpes' =>  '3001809'],    // Paulo Cesar Endo
            ['codpes' =>  '4865573'],    // Pedro Fernando da Silva
            ['codpes' =>  '7908099'],    // Renata El Rafihi Ferreira
            ['codpes' =>  '1841928'],    // Rogério Lernet
        ];

        // adiciona registro na tabela orientadores
        foreach ($orientadores as $orientador)
            $orientador = Orientador::create($orientador);

        // adiciona registros na tabela orientadores_linhaspesquisa
        foreach (Orientador::whereIn('codpes', ['2085249', '1841928', '907384', '4864151', '3001174'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Desenvolvimento Humano e Aprendizagem')->first()->id);

        foreach (Orientador::whereIn('codpes', ['791212', '339731'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Desenvolvimento Humano e Avaliação Psicológica')->first()->id);

        foreach (Orientador::whereIn('codpes', ['4865573'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Psicologia, Instituições e Sociedade: Mediações do Desenvolvimento Humano')->first()->id);

        foreach (Orientador::whereIn('codpes', ['3001809'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Psicanálise Política: Cultura e Desenvolvimento Humano')->first()->id);

        foreach (Orientador::whereIn('codpes', ['2163232', '647530'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Psicologia Escolar, Educação e Políticas Públicas')->first()->id);

        foreach (Orientador::whereIn('codpes', ['2103422', '10229702'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Psicanálise, Sofrimento e Política')->first()->id);

        foreach (Orientador::whereIn('codpes', ['5811484', '962810', '90061', '7915462', '1637670'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Psicanálise, Intersubjetividade e Configurações Vinculares')->first()->id);

        foreach (Orientador::whereIn('codpes', ['5593721', '3386535', '5710209', '96853', '2088882', '774870', '2163232', '7908099'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Intervenções Clínicas em Saúde Mental: Diagnóstico, Ação Terapêutica e Prevenção')->first()->id);

        foreach (Orientador::whereIn('codpes', ['821761', '3333504', '1489943', '2557349', '2352751'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Análise do Comportamento')->first()->id);

        foreach (Orientador::whereIn('codpes', ['576462', '94249', '77048', '8314620', '1505248', '7811859'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Comportamento Animal e Etologia Humana')->first()->id);

        foreach (Orientador::whereIn('codpes', ['3257875', '3648589'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Sensação, Perceção e Cognição')->first()->id);

        foreach (Orientador::whereIn('codpes', ['3463419', '2142872'])->get() as $orientador)
            $orientador->linhaspesquisa()->attach(LinhaPesquisa::where('nome', 'Problemas Teóricos e Metodológicos')->first()->id);
    }
}
