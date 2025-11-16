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
        Schema::table('pedidos', function (Blueprint $table) {
            // Añadimos las columnas de dirección
            $table->string('calle')->nullable()->after('metodo_pago');
            $table->string('colonia')->nullable()->after('calle');
            $table->string('municipio_ciudad')->nullable()->after('colonia');
            $table->string('codigo_postal')->nullable()->after('municipio_ciudad');
            $table->string('estado_direccion')->nullable()->after('codigo_postal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Esto es para poder revertir los cambios
            $table->dropColumn([
                'calle',
                'colonia',
                'municipio_ciudad',
                'codigo_postal',
                'estado_direccion'
            ]);
        });
    }
};