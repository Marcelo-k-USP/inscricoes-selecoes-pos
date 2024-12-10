<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelecoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selecoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('estado', 90);
            $table->string('descricao', 255)->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->json('template')->nullable();
            $table->json('settings')->nullable();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('programa_id')->constrained('programas');
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
        Schema::dropIfExists('selecoes');
    }
}
