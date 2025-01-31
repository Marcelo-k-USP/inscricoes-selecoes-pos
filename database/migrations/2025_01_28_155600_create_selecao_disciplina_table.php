<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelecaoDisciplinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selecao_disciplina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('selecao_id')->constrained('selecoes')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplinas')->onDelete('cascade');
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
        Schema::dropIfExists('selecao_disciplina');
    }
}
