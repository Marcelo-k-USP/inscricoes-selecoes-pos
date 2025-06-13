<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSelecoesTableAddFluxoContinuo extends Migration
{
    public function up()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->boolean('fluxo_continuo')->default(false);
            $table->integer('boleto_offset_vencimento')->nullable();
        });
    }

    public function down()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->dropColumn('fluxo_continuo');
            $table->dropColumn('boleto_offset_vencimento');
        });
    }
}
