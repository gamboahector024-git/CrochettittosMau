<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalles_pedido', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->foreignId('id_pedido')
                  ->constrained('pedidos', 'id_pedido') // Especificar columna referenciada
                  ->cascadeOnDelete();
            $table->foreignId('id_producto')
                  ->constrained('productos', 'id_producto')
                  ->restrictOnDelete(); // Evita borrar productos con pedidos
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->timestamps();
            
            // Index para mejorar búsquedas por pedido
            $table->index('id_pedido');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalles_pedido');
    }
};