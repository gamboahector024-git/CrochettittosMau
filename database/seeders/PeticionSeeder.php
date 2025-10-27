<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeticionSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar un usuario existente
        $usuarioId = DB::table('usuarios')->min('id_usuario');
        if (!$usuarioId) {
            $usuarioId = DB::table('usuarios')->insertGetId([
                'nombre' => 'Cliente',
                'apellido' => 'Peticiones',
                'email' => 'peticiones+' . Str::random(5) . '@mail.test',
                'password_hash' => bcrypt('123456'),
                'direccion' => 'Dirección de prueba 456',
                'telefono' => '555-1111',
                'rol' => 'cliente',
                'fecha_registro' => now(),
            ]);
        }

        $rows = [
            [
                'id_usuario' => $usuarioId,
                'titulo' => 'Llavero personalizado',
                'descripcion' => 'Quiero un llavero con forma de corazón y mi nombre.',
                'imagen_referencia' => null,
                'estado' => 'pendiente',
                'respuesta_admin' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'id_usuario' => $usuarioId,
                'titulo' => 'Arreglo floral especial',
                'descripcion' => 'Arreglo con rosas y lilas para cumpleaños.',
                'imagen_referencia' => null,
                'estado' => 'en revisión',
                'respuesta_admin' => 'Estamos revisando opciones y costos.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'id_usuario' => $usuarioId,
                'titulo' => 'Producto combinado',
                'descripcion' => 'Un set con flores y un llavero a juego.',
                'imagen_referencia' => null,
                'estado' => 'aceptada',
                'respuesta_admin' => 'Podemos hacerlo en 7 días hábiles.',
                'created_at' => now()->subDay(),
                'updated_at' => now(),
            ],
            [
                'id_usuario' => $usuarioId,
                'titulo' => 'Pedido rechazado',
                'descripcion' => 'No podemos cumplir con este pedido especial.',
                'imagen_referencia' => null,
                'estado' => 'rechazada',
                'respuesta_admin' => 'Lo sentimos, no tenemos los materiales requeridos.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id_usuario' => $usuarioId,
                'titulo' => 'Pedido completado',
                'descripcion' => 'Muñeco de crochet personalizado.',
                'imagen_referencia' => null,
                'estado' => 'completada',
                'respuesta_admin' => 'Entregado según lo acordado.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(1),
            ],
        ];

        DB::table('peticiones')->insert($rows);
    }
}
