<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArquivoInscricaoTableAddDisciplina extends Migration
{
    public function up()
    {
        Schema::table('arquivo_inscricao', function (Blueprint $table) {
            $table->string('disciplina')->nullable();
        });
    }

    public function down()
    {
        Schema::table('arquivo_inscricao', function (Blueprint $table) {
            $table->dropColumn('disciplina');
        });
    }
}
