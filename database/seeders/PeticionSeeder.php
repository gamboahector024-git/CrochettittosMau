<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeticionSeeder extends Seeder
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

        $rows = [
            // Peticiones de María
            [
                'id_usuario' => $mariaId,
                'titulo' => 'Llavero personalizado con iniciales MG',
                'descripcion' => 'Quiero un llavero tejido en forma de corazón con mis iniciales MG bordadas. Preferencia de colores: rosa y blanco. Tamaño pequeño (8cm).',
                'imagen_referencia' => null,
                'estado' => 'pendiente',
                'respuesta_admin' => null,
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(12),
            ],
            [
                'id_usuario' => $mariaId,
                'titulo' => 'Muñeco amigurumi gato personalizado',
                'descripcion' => 'Quiero un muñeco a crochet con forma de gato, tamaño mediano (20cm), con ojos de botón verdes y detalles en rosa. Puede ser similar al que venden en la tienda.',
                'imagen_referencia' => null,
                'estado' => 'aceptada',
                'respuesta_admin' => 'Aceptamos tu pedido. Tiempo de entrega: 10 días hábiles. Costo total: $399.00. Comenzamos la próxima semana.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(3),
            ],
            // Petición de Carlos
            [
                'id_usuario' => $carlosId,
                'titulo' => 'Arreglo floral para aniversario',
                'descripcion' => 'Necesito un arreglo floral para celebrar aniversario. Colores: rojo, blanco y dorado. Tamaño mediano. Presupuesto máximo: $300.',
                'imagen_referencia' => null,
                'estado' => 'en revisión',
                'respuesta_admin' => 'Estamos revisando disponibilidad de materiales y presupuesto. Nos contactaremos en 24 horas con opciones.',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(4),
            ],
        ];

        DB::table('peticiones')->insert($rows);
    }
}
