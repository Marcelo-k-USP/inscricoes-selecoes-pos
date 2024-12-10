<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
            PermissionSeeder::class,        // adiciona permissions
            SetorReplicadoSeeder::class,    // adiciona todos os setores da unidade do replicado
            ProgramaSeeder::class,          // adiciona programas
            CategoriaSeeder::class,         // adiciona categorias
            SelecaoSeeder::class,           // adiciona seleções
            LinhaPesquisaSeeder::class,     // adiciona linhas de pesquisa
            ParametroSeeder::class,         // adiciona parâmetros
        ]);
    }
}
