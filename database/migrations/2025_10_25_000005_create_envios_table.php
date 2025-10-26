<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id('id_envio');
            $table->foreignId('id_pedido')->constrained('pedidos', 'id_pedido')->onDelete('cascade');
            $table->string('empresa', 100)->nullable();
            $table->string('codigo_rastreo', 100)->nullable();
            $table->enum('estado', ['pendiente', 'en tránsito', 'entregado', 'devuelto'])->default('pendiente');
            $table->timestamp('fecha_envio')->nullable();
            $table->date('fecha_entrega_estimada')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('envios');
    }
};
