<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSelecoesTableUpdateNome extends Migration
{
    public function up()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->string('nome')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('selecoes', function (Blueprint $table) {
            $table->string('nome')->nullable(false)->change();
        });
    }
}
