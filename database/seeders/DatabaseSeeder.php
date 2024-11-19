<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // desativando eventos no seeder
        // ...

        $this->call([
            SetorReplicadoSeeder::class,    // adiciona todos os setores da unidade do replicado
            CategoriaSeeder::class,         // adiciona categorias
            SelecaoSeeder::class,           // adiciona seleções
            LinhasPesquisaSeeder::class,    // adiciona linhas de pesquisa
        ]);
    }
}
