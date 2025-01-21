<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinhaPesquisaSelecaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linhapesquisa_selecao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('linhapesquisa_id')->constrained('linhaspesquisa')->onDelete('cascade');
            $table->foreignId('selecao_id')->constrained('selecoes')->onDelete('cascade');
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
        Schema::dropIfExists('linhapesquisa_selecao');
    }
}
