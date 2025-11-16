<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Le decimos a la columna que ahora SÍ permite valores NULL
            $table->string('direccion_envio')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Revierte el cambio (vuelve a ser NO nullable)
            $table->string('direccion_envio')->nullable(false)->change();
        });
    }
};