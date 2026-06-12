<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgramasTableAddSigla extends Migration
{
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('sigla')->nullable()->after('nome');
        });
    }

    public function down()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn('sigla');
        });
    }
}
