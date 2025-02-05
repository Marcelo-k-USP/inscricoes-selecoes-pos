<?php

namespace Database\Seeders;

use App\Models\Nivel;
use App\Models\Programa;
use Illuminate\Database\Seeder;

class NivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $niveis = [
            [
                'nome' => 'Mestrado',
            ],
            [
                'nome' => 'Doutorado',
            ],
            [
                'nome' => 'Doutorado Direto',
            ],
        ];

        // adiciona registros na tabela niveis
        foreach ($niveis as $nivel)
            Nivel::create($nivel);

        // adiciona registros na tabela nivel_programa
        foreach (Nivel::all() as $nivel)
            foreach (Programa::all() as $programa)
                $nivel->programas()->attach($programa->id);
    }
}
