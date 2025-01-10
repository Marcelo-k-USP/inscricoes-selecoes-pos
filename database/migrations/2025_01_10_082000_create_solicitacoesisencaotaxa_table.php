<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitacoesIsencaoTaxaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacoesisencaotaxa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('estado', 90);
            $table->json('extras')->nullable();

            /* Relacionamentos */
            $table->foreignId('selecao_id')->constrained('selecoes');

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
        Schema::dropIfExists('solicitacoesisencaotaxa');
    }
}
