<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Aquí agregamos la columna 'ultima_actividad'
            // Usamos nullable() para que no de error con los usuarios que ya existen
            $table->timestamp('ultima_actividad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Esto elimina la columna si alguna vez deshaces la migración
            $table->dropColumn('ultima_actividad');
        });
    }
};