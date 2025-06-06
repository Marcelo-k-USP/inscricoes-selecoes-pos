<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelecaoNivelLinhaPesquisaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selecao_nivellinhapesquisa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selecao_id')->constrained('selecoes')->onDelete('cascade');
            $table->foreignId('nivellinhapesquisa_id')->constrained('nivel_linhapesquisa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selecao_nivellinhapesquisa');
    }
}
