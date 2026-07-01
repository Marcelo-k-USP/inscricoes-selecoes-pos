<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSelecoesTableUpdateCampos extends Migration
{
    public function up()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->renameColumn('email_inscricaoaprovacao_texto', 'email_inscricaomatriculaaprovacao_texto');
            $table->renameColumn('email_inscricaorejeicao_texto', 'email_inscricaomatricularejeicao_texto');
            $table->renameColumn('inscricoes_datahora_inicio', 'inscricoesmatriculas_datahora_inicio');
            $table->renameColumn('inscricoes_datahora_fim', 'inscricoesmatriculas_datahora_fim');
        });
    }

    public function down()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->renameColumn('email_inscricaomatriculaaprovacao_texto', 'email_inscricaoaprovacao_texto');
            $table->renameColumn('email_inscricaomatricularejeicao_texto', 'email_inscricaorejeicao_texto');
            $table->renameColumn('inscricoesmatriculas_datahora_inicio', 'inscricoes_datahora_inicio');
            $table->renameColumn('inscricoesmatriculas_datahora_fim', 'inscricoes_datahora_fim');
        });
    }
}
