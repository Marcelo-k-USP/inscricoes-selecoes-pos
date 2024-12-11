<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Seeder;

class ProgramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $programas = [
            [
                'nome' => 'Programa 1',
                'descricao' => 'Programa 1'
            ],
            [
                'nome' => 'Programa 2',
                'descricao' => 'Programa 2'
            ],
        ];

        // adiciona registros na tabela programas
        foreach ($programas as $programa)
            Programa::create($programa);
    }
}
