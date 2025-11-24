<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('email', 255)->unique();
            $table->string('password_hash', 255);
            $table->text('direccion')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->enum('rol', ['cliente', 'admin'])->default('cliente');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('ultima_actividad')->nullable()->comment('Última interacción del usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
