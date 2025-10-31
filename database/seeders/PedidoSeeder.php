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
        // Obtener usuarios creados por UsuarioSeeder
        $usuarios = DB::table('usuarios')->pluck('id_usuario', 'nombre');
        $mariaId = $usuarios['María'] ?? null;
        $carlosId = $usuarios['Carlos'] ?? null;

        if (!$mariaId || !$carlosId) {
            return; // No continuar si no existen los usuarios
        }

        // Asegurar que existan productos (dependencia de ProductoSeeder)
        $productoIds = DB::table('productos')->pluck('id_producto')->all();
        if (count($productoIds) === 0) {
            return; // No continuar si no existen productos
        }

        // Crear pedidos de prueba
        $pedidos = [
            // Pedidos de María
            [
                'id_usuario' => $mariaId,
                'fecha_pedido' => now()->subDays(20),
                'total' => 329.90,
                'estado' => 'entregado',
                'direccion_envio' => 'Calle Principal 123, Apto 4B',
                'metodo_pago' => 'tarjeta',
                'empresa_envio' => 'FedEx',
                'codigo_rastreo' => 'FDX'.Str::upper(Str::random(10)),
                'fecha_envio' => now()->subDays(18),
                'fecha_entrega_estimada' => now()->subDays(12)->toDateString(),
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(12),
            ],
            [
                'id_usuario' => $mariaId,
                'fecha_pedido' => now()->subDays(8),
                'total' => 249.00,
                'estado' => 'procesando',
                'direccion_envio' => 'Calle Principal 123, Apto 4B',
                'metodo_pago' => 'transferencia',
                'empresa_envio' => 'DHL',
                'codigo_rastreo' => 'DHL'.Str::upper(Str::random(10)),
                'fecha_envio' => now()->subDays(6),
                'fecha_entrega_estimada' => now()->addDays(1)->toDateString(),
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(1),
            ],
            // Pedido de Carlos
            [
                'id_usuario' => $carlosId,
                'fecha_pedido' => now()->subDays(3),
                'total' => 79.90,
                'estado' => 'pendiente',
                'direccion_envio' => 'Avenida Central 456, Piso 2',
                'metodo_pago' => 'efectivo',
                'empresa_envio' => null,
                'codigo_rastreo' => null,
                'fecha_envio' => null,
                'fecha_entrega_estimada' => null,
                'created_at' => now()->subDays(3),
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
