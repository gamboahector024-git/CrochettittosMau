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
            $table->foreignId('id_peticion')->nullable();
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
            // Nuevos campos integrados (antes en envios)
            $table->string('metodo_pago', 50)->nullable();
            $table->string('empresa_envio', 100)->nullable();
            $table->string('codigo_rastreo', 100)->nullable();
            $table->timestamp('fecha_envio')->nullable();
            $table->date('fecha_entrega_estimada')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};