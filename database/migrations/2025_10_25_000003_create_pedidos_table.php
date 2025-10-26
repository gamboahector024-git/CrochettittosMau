<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->foreignId('id_usuario')
                  ->constrained('usuarios', 'id_usuario')
                  ->cascadeOnDelete();
            $table->timestamp('fecha_pedido')->useCurrent();
            $table->decimal('total', 10, 2);
            $table->enum('estado', [
                'pendiente', 
                'procesando', 
                'enviado', 
                'entregado', 
                'cancelado'
            ])->default('pendiente');
            $table->text('direccion_envio');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};