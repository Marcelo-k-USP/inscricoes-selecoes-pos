<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParametrosTableAddLinkAcompanhamentoEspeciais extends Migration
{
    public function up()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->string('link_acompanhamento_especiais')->nullable();
        });
    }

    public function down()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn('link_acompanhamento_especiais');
        });
    }
}
