<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinhasPesquisaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linhaspesquisa', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->integer('codpes_docente');
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
        Schema::dropIfExists('linhaspesquisa');
    }
}
