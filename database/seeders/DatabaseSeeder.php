<?php

namespace Database\Seeders;

use App\Models\Inscricao;
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
        Inscricao::flushEventListeners();

        $this->call([
            PermissionSeeder::class,        // adiciona permissions
            SetorReplicadoSeeder::class,    // adiciona todos os setores da unidade do replicado
            ProgramaSeeder::class,          // adiciona programas
            CategoriaSeeder::class,         // adiciona categorias
            SelecaoSeeder::class,           // adiciona seleções
            LinhaPesquisaSeeder::class,     // adiciona linhas de pesquisa/temas
            OrientadorSeeder::class,        // adiciona orientadores
            ParametroSeeder::class,         // adiciona parâmetros
            MotivoIsencaoTaxaSeeder::class, // adiciona motivos de isenção de taxa
        ]);
    }
}
