<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTiposArquivoTableAddObrigatorioCondicao extends Migration
{
    public function up()
    {
        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->string('obrigatorio_condicao_campo')->nullable()->after('obrigatorio');
            $table->string('obrigatorio_condicao_valor')->nullable()->after('obrigatorio_condicao_campo');
        });
    }

    public function down()
    {
        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->dropColumn(['obrigatorio_condicao_campo', 'obrigatorio_condicao_valor']);
        });
    }
}
