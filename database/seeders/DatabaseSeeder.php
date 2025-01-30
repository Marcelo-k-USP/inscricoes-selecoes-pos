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
            FuncaoSeeder::class,            // adiciona funções
            SelecaoSeeder::class,           // adiciona seleções
            NivelSeeder::class,             // adiciona níveis
            LinhaPesquisaSeeder::class,     // adiciona linhas de pesquisa/temas
            DisciplinaSeeder::class,        // adiciona disciplinas
            ParametroSeeder::class,         // adiciona parâmetros
            MotivoIsencaoTaxaSeeder::class, // adiciona motivos de isenção de taxa
        ]);
    }
}
