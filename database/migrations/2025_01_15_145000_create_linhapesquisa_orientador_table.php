<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinhaPesquisaOrientadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linhapesquisa_orientador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('linhapesquisa_id')->constrained('linhaspesquisa')->onDelete('cascade');
            $table->string('orientador_id');
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
        Schema::dropIfExists('linhapesquisa_orientador');
    }
}
