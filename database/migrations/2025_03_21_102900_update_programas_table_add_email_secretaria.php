<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgramasTableAddEmailSecretaria extends Migration
{
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('email_secretaria')->nullable();
        });
    }

    public function down()
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn('email_secretaria');
        });
    }
}
