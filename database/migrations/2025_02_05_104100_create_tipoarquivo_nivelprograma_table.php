<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoArquivoNivelProgramaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipoarquivo_nivelprograma', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipoarquivo_id')->constrained('tiposarquivo')->onDelete('cascade');
            $table->foreignId('nivelprograma_id')->constrained('nivel_programa')->onDelete('cascade');
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
        Schema::dropIfExists('tipoarquivo_nivelprograma');
    }
}
