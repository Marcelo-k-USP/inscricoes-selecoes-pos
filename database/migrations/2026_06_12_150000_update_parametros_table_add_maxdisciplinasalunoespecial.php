<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParametrosTableAddMaxDisciplinasAlunoEspecial extends Migration
{
    public function up()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->integer('max_disciplinas_aluno_especial')->nullable();
        });
    }

    public function down()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn('max_disciplinas_aluno_especial');
        });
    }
}
