<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParametrosTableAddBoletoMomentoEnvio extends Migration
{
    public function up()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->string('boleto_momento_envio')->nullable()->after('boleto_estrutura_hierarquica');
        });
    }

    public function down()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn('boleto_momento_envio');
        });
    }
}
