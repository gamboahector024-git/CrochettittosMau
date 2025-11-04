<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carrito_detalles', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->foreignId('id_carrito')->constrained('carritos', 'id_carrito')->onDelete('cascade');
            $table->foreignId('id_producto')->constrained('productos', 'id_producto')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            // Un producto por carrito (evitar duplicados del mismo producto)
            $table->unique(['id_carrito', 'id_producto'], 'carrito_detalles_unique_producto_en_carrito');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carrito_detalles');
    }
};
