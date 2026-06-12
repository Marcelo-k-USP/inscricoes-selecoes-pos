<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('programas', function (Blueprint $table) {
            // Criamos a coluna apenas se ela NÃO existir (proteção extra)
            if (!Schema::hasColumn('programas', 'parametro_id')) {
                $table->unsignedBigInteger('parametro_id')->nullable()->after('id');
            }
        });

        Schema::table('programas', function (Blueprint $table) {
            // Criamos a FK em um comando separado
            $table->foreign('parametro_id')
                ->references('id')
                ->on('parametros')
                ->onDelete('set null');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            //
        });
    }
};
