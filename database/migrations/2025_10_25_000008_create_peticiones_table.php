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

        // Agregar FK a pedidos.id_peticion ahora que 'peticiones' existe
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'id_peticion')) {
                $table->foreign('id_peticion')
                      ->references('id_peticion')
                      ->on('peticiones')
                      ->nullOnDelete();
            }
        });
    }

    public function down()
    {
        // Soltar FK desde pedidos antes de eliminar 'peticiones'
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'id_peticion')) {
                try {
                    $table->dropForeign(['id_peticion']);
                } catch (\Throwable $e) {
                    // Ignorar si ya fue eliminada o no existe
                }
            }
        });
        Schema::dropIfExists('peticiones');
    }
};
