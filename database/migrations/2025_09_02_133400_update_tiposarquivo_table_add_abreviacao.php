<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTiposArquivoTableAddAbreviacao extends Migration
{
    public function up()
    {
        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->string('abreviacao')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tiposarquivo', function (Blueprint $table) {
            $table->dropColumn('abreviacao');
        });
    }
}
