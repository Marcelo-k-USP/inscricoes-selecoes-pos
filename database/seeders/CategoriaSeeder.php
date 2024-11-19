<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            [
                'nome' => 'Aluno Regular',
                'descricao' => 'Mestrado e Doutorado'
            ],
            [
                'nome' => 'Aluno Especial',
                'descricao' => 'Em disciplinas'
            ],
        ];

        // adiciona registros na tabela categorias
        foreach ($categorias as $categoria)
            Categoria::create($categoria);
    }
}
