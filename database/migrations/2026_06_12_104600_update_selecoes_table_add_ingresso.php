<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSelecoesTableAddIngresso extends Migration
{
    public function up()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->string('nome')->nullable()->change();
            $table->integer('ingresso_semestre')->nullable();
            $table->integer('ingresso_ano')->nullable();
        });
    }

    public function down()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->string('nome')->nullable(false)->change();
            $table->dropColumn('ingresso_semestre');
            $table->dropColumn('ingresso_ano');
        });
    }
}
