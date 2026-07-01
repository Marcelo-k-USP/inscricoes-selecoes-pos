<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArquivoInscricaoTableDeleteDisciplina extends Migration
{
    public function up()
    {
        Schema::table('arquivo_inscricao', function (Blueprint $table) {
            //ainda não, para não perder os dados... $table->dropColumn('disciplina');
        });
    }

    public function down()
    {
        Schema::table('arquivo_inscricao', function (Blueprint $table) {
            //ainda não, para não perder os dados... $table->string('disciplina')->nullable()->after('tipo');
        });
    }
}
