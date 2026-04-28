<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Uspdev\Replicado\Posgraduacao;
use App\Models\Programa;

class FuncaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /**
     * Por enquanto só preenche automaticamente os Docentes do Programa, ainda não há criação automática para:
     * a) Secretários(as) do Programa
     * b) Coordenadores do Programa
     * c) Serviço de Pós-Graduação
     * d) Coordenadores da Pós-Graduação
    */
   public function run()
    {
        $programas = Posgraduacao::programas();
        
        foreach($programas as $programa)
        {
            $programaSistema = Programa::where('nome', 'LIKE', "{$programa['nomcur']}%")->first();
            if (!$programaSistema) {
                continue; 
            }

            $docentes = Posgraduacao::orientadores($programa['codare']);
            
            foreach($docentes as $docente)
            {
                $user = User::findOrCreateFromReplicado($docente['codpes']);
                
                if ($user) {
                    $jaAssociado = $user->programas()
                        ->where('programa_id', $programaSistema->id)
                        ->wherePivot('funcao', 'Docentes do Programa')
                        ->exists();

                    if (!$jaAssociado) {
                        $user->associarProgramaFuncao($programaSistema->nome, 'Docentes do Programa');
                    }
                }
            }
        }
    }
}
