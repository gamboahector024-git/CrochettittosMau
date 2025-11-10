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
        $sofiaId = $usuarios['Sofía'] ?? null;
        $diegoId = $usuarios['Diego'] ?? null;
        $valeriaId = $usuarios['Valeria'] ?? null;

        if (!$sofiaId || !$diegoId || !$valeriaId) {
            return; // No continuar si no existen los usuarios
        }

        // Asegurar que existan productos (dependencia de ProductoSeeder)
        $productoIds = DB::table('productos')->pluck('id_producto')->all();
        if (count($productoIds) === 0) {
            return; // No continuar si no existen productos
        }

        // Crear pedidos de prueba
        $pedidos = [
            // Pedido entregado
            [
                'id_usuario' => $sofiaId,
                'fecha_pedido' => now()->subDays(15),
                'total' => 399.00,
                'estado' => 'entregado',
                'calle' => 'Av. Revolución 1500',
                'colonia' => 'San Pedro',
                'municipio_ciudad' => 'Álvaro Obregón',
                'codigo_postal' => '01080',
                'estado_direccion' => 'Ciudad de México',
                'metodo_pago' => 'tarjeta',
                'empresa_envio' => 'FedEx',
                'codigo_rastreo' => 'FX'.Str::upper(Str::random(10)),
                'fecha_envio' => now()->subDays(10),
                'fecha_entrega_estimada' => now()->subDays(5),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(5),
            ],
            // Pedido cancelado
            [
                'id_usuario' => $sofiaId,
                'fecha_pedido' => now()->subDays(12),
                'total' => 199.00,
                'estado' => 'cancelado',
                'calle' => 'Av. Revolución 1500',
                'colonia' => 'San Pedro',
                'municipio_ciudad' => 'Álvaro Obregón',
                'codigo_postal' => '01080',
                'estado_direccion' => 'Ciudad de México',
                'metodo_pago' => 'paypal',
                'empresa_envio' => null,
                'codigo_rastreo' => null,
                'fecha_envio' => null,
                'fecha_entrega_estimada' => null,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10),
            ],
            // Pedido en proceso
            [
                'id_usuario' => $diegoId,
                'fecha_pedido' => now()->subDays(7),
                'total' => 149.50,
                'estado' => 'procesando',
                'calle' => 'Calle Morelos 45',
                'colonia' => 'Centro',
                'municipio_ciudad' => 'Cuauhtémoc',
                'codigo_postal' => '06000',
                'estado_direccion' => 'Ciudad de México',
                'metodo_pago' => 'transferencia',
                'empresa_envio' => null,
                'codigo_rastreo' => null,
                'fecha_envio' => null,
                'fecha_entrega_estimada' => null,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            // Pedido enviado
            [
                'id_usuario' => $diegoId,
                'fecha_pedido' => now()->subDays(4),
                'total' => 299.00,
                'estado' => 'enviado',
                'calle' => 'Calle Morelos 45',
                'colonia' => 'Centro',
                'municipio_ciudad' => 'Cuauhtémoc',
                'codigo_postal' => '06000',
                'estado_direccion' => 'Ciudad de México',
                'metodo_pago' => 'tarjeta',
                'empresa_envio' => 'DHL',
                'codigo_rastreo' => 'DH'.Str::upper(Str::random(10)),
                'fecha_envio' => now()->subDays(2),
                'fecha_entrega_estimada' => now()->addDays(3),
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(2),
            ],
            // Pedido pendiente
            [
                'id_usuario' => $valeriaId,
                'fecha_pedido' => now()->subDays(2),
                'total' => 249.00,
                'estado' => 'pendiente',
                'calle' => 'Paseo de la Reforma 222',
                'colonia' => 'Juárez',
                'municipio_ciudad' => 'Cuauhtémoc',
                'codigo_postal' => '06600',
                'estado_direccion' => 'Ciudad de México',
                'metodo_pago' => 'paypal',
                'empresa_envio' => null,
                'codigo_rastreo' => null,
                'fecha_envio' => null,
                'fecha_entrega_estimada' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
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
                DB::table('pedido_detalles')->insert([
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
