<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peticiones', function (Blueprint $table) {
            $table->id('id_peticion');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->string('imagen_referencia', 255)->nullable();
            $table->enum('estado', ['pendiente', 'en revisión', 'aceptada', 'rechazada', 'completada'])->default('pendiente');
            $table->text('respuesta_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peticiones');
    }
};
