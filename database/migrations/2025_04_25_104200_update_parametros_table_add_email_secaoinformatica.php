<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateParametrosTableAddEmailSecaoInformatica extends Migration
{
    public function up()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->string('email_secaoinformatica')->nullable();
        });
    }

    public function down()
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn('email_secaoinformatica');
        });
    }
}
