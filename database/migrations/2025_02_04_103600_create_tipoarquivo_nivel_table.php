<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoArquivoNivelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipoarquivo_nivel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipoarquivo_id')->constrained('tiposarquivo')->onDelete('cascade');
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
        Schema::dropIfExists('tipoarquivo_nivel');
    }
}
