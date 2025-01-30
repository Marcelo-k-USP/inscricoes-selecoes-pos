<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinhaPesquisaNivelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linhapesquisa_nivel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('linhapesquisa_id')->constrained('linhaspesquisa')->onDelete('cascade');
            $table->foreignId('nivel_id')->constrained('niveis')->onDelete('cascade');
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
        Schema::dropIfExists('linhapesquisa_nivel');
    }
}
