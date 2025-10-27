<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar que haya al menos 1 usuario
        $usuarioId = DB::table('usuarios')->min('id_usuario');
        if (!$usuarioId) {
            // Si no existe, crear uno rápido (en caso de fallo de AdminSeeder)
            $usuarioId = DB::table('usuarios')->insertGetId([
                'nombre' => 'Cliente',
                'apellido' => 'Pruebas',
                'email' => 'cliente+'.Str::random(5).'@mail.test',
                'password_hash' => bcrypt('123456'),
                'direccion' => 'Dirección de prueba 123',
                'telefono' => '555-0000',
                'rol' => 'cliente',
                'fecha_registro' => now(),
            ]);
        }

        // Asegurar que existan productos (dependencia de ProductoSeeder)
        $productoIds = DB::table('productos')->pluck('id_producto')->take(3)->all();
        if (count($productoIds) === 0) {
            // Crear productos mínimos
            DB::table('productos')->insert([
                [
                    'nombre' => 'Producto Test 1',
                    'descripcion' => 'Desc 1',
                    'precio' => 100,
                    'stock' => 100,
                    'id_categoria' => null,
                    'imagen_url' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nombre' => 'Producto Test 2',
                    'descripcion' => 'Desc 2',
                    'precio' => 200,
                    'stock' => 50,
                    'id_categoria' => null,
                    'imagen_url' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
            $productoIds = DB::table('productos')->pluck('id_producto')->take(3)->all();
        }

        // Crear pedidos de prueba
        $pedidos = [
            [
                'id_usuario' => $usuarioId,
                'fecha_pedido' => now()->subDays(3),
                'total' => 350.75,
                'estado' => 'procesando',
                'direccion_envio' => 'Calle 1 #123',
                'metodo_pago' => 'tarjeta',
                'empresa_envio' => 'DHL',
                'codigo_rastreo' => 'DHL'.Str::upper(Str::random(8)),
                'fecha_envio' => now()->subDays(2),
                'fecha_entrega_estimada' => now()->addDays(3)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_usuario' => $usuarioId,
                'fecha_pedido' => now()->subDay(),
                'total' => 120.00,
                'estado' => 'pendiente',
                'direccion_envio' => 'Avenida 2 #456',
                'metodo_pago' => 'transferencia',
                'empresa_envio' => null,
                'codigo_rastreo' => null,
                'fecha_envio' => null,
                'fecha_entrega_estimada' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($pedidos as $p) {
            $idPedido = DB::table('pedidos')->insertGetId($p);

            // Detalles del pedido (usar 1-2 productos)
            $elegidos = array_slice($productoIds, 0, rand(1, min(2, count($productoIds))));
            $total = 0;
            foreach ($elegidos as $prodId) {
                $precio = DB::table('productos')->where('id_producto', $prodId)->value('precio') ?? 100;
                $cantidad = rand(1, 3);
                $subtotal = $precio * $cantidad;
                $total += $subtotal;
                DB::table('detalles_pedido')->insert([
                    'id_pedido' => $idPedido,
                    'id_producto' => $prodId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // Actualizar total real si difiere
            DB::table('pedidos')->where('id_pedido', $idPedido)->update(['total' => $total]);
        }
    }
}
