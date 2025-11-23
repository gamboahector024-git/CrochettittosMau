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
            $table->foreignId('id_categoria')->nullable()->constrained('categorias', 'id_categoria')->nullOnDelete();
            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->integer('cantidad')->default(1);
            $table->string('imagen_referencia', 255)->nullable();
            
            // Dirección de entrega
            $table->string('calle', 255)->nullable();
            $table->string('colonia', 255)->nullable();
            $table->string('municipio_ciudad', 255)->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('estado_direccion', 100)->nullable();
            
            $table->enum('estado', ['pendiente', 'en revisión', 'aceptada', 'rechazada', 'completada'])->default('pendiente');
            
            // Respuesta del admin
            $table->text('respuesta_admin')->nullable();
            $table->decimal('precio_propuesto', 10, 2)->nullable();
            $table->timestamp('fecha_respuesta_admin')->nullable();
            
            // Respuesta del cliente
            $table->enum('respuesta_cliente', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->timestamp('fecha_respuesta_cliente')->nullable();
            
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
