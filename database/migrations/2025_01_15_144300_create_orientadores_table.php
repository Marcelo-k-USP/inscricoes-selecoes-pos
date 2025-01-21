<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrientadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orientadores', function (Blueprint $table) {
            $table->id();
            $table->string('codpes');

            // necessários caso o orientador seja externo à unidade, pois ele não estará no Replicado local:
            $table->string('nome')->nullable();
            $table->string('email')->nullable();

            $table->boolean('externo')->default(0);    // indica se o orientador é externo à unidade
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
        Schema::dropIfExists('orientadores');
    }
}
