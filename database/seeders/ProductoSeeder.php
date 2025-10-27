<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener categorías existentes (sembradas por CategoriaSeeder)
        $cats = DB::table('categorias')->pluck('id_categoria', 'nombre');
        $idLlaveros = $cats['Llaveros'] ?? null;
        $idFlores = $cats['Flores'] ?? null;
        $idPers = $cats['Personalizados'] ?? null;

        $productos = [
            [
                'nombre' => 'Llavero Corazón',
                'descripcion' => 'Llavero tejido en forma de corazón',
                'precio' => 79.90,
                'stock' => 50,
                'id_categoria' => $idLlaveros,
                'imagen_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ramo de Rosas',
                'descripcion' => 'Arreglo floral con rosas artificiales',
                'precio' => 249.00,
                'stock' => 20,
                'id_categoria' => $idFlores,
                'imagen_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Muñeco Personalizado',
                'descripcion' => 'Muñeco a crochet personalizado',
                'precio' => 399.00,
                'stock' => 10,
                'id_categoria' => $idPers,
                'imagen_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Evitar duplicados por nombre
        foreach ($productos as $p) {
            $exists = DB::table('productos')->where('nombre', $p['nombre'])->exists();
            if (!$exists) {
                DB::table('productos')->insert($p);
            }
        }
    }
}
