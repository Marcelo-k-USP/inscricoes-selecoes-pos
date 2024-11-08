<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Processo;

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
            ProcessoSeeder::class,          // adiciona processos
            SelecaoSeeder::class,           // adiciona seleções
        ]);
    }
}
