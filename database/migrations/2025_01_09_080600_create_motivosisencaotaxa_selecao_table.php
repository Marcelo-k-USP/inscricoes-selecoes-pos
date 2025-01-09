<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivosIsencaoTaxaSelecaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivoisencaotaxa_selecao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motivoisencaotaxa_id')->constrained('motivosisencaotaxa')->onDelete('cascade');
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
        Schema::dropIfExists('motivoisencaotaxa_selecao');
    }
}
