<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTiposArquivoTableDropAlunoEspecial extends Migration
{
    public function up()
    {
        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->dropColumn('aluno_especial');
        });
    }

    public function down()
    {
        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->boolean('aluno_especial')->default(false);
        });
    }
}
