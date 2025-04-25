<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgramasTableAddLinkAcompanhamento extends Migration
{
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('link_acompanhamento')->nullable();
        });
    }

    public function down()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn('link_acompanhamento');
        });
    }
}
