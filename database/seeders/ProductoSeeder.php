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
                'nombre' => 'Llavero Corazón Rosa',
                'descripcion' => 'Llavero tejido a mano en forma de corazón con detalles en rosa. Perfecto como regalo o accesorio personal. Medida: 8cm',
                'precio' => 79.90,
                'stock' => 50,
                'id_categoria' => $idLlaveros,
                'imagen_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Ramo de Rosas Artificiales',
                'descripcion' => 'Hermoso arreglo floral con rosas artificiales de alta calidad. Incluye florero de cristal. Ideal para decoración o regalo especial.',
                'precio' => 249.00,
                'stock' => 20,
                'id_categoria' => $idFlores,
                'imagen_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Muñeco Amigurumi Gato',
                'descripcion' => 'Adorable muñeco a crochet con forma de gato. Hecho a mano con materiales de calidad. Medida: 20cm. Personalizable en colores.',
                'precio' => 399.00,
                'stock' => 10,
                'id_categoria' => $idPers,
                'imagen_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Nuevos productos para más realismo
            [
                'nombre' => 'Bufanda de Lana Invernal',
                'descripcion' => 'Bufanda suave y cálida hecha de lana 100% natural. Disponible en varios colores, ideal para el frío. Medida: 180cm x 30cm.',
                'precio' => 149.50,
                'stock' => 30,
                'id_categoria' => $idPers,
                'imagen_url' => 'https://placehold.co/600x400?text=Bufanda+Lana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Jarrón Decorativo Floral',
                'descripcion' => 'Jarrón de cerámica con diseño floral, perfecto para arreglos frescos o artificiales. Altura: 25cm, colores variados.',
                'precio' => 89.99,
                'stock' => 15,
                'id_categoria' => $idFlores,
                'imagen_url' => 'https://placehold.co/600x400?text=Jarrón+Floral',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Llavero Personalizado con Iniciales',
                'descripcion' => 'Llavero con iniciales bordadas a mano. Elige tu letra y color. Material: madera y cuerda. Medida: 5cm.',
                'precio' => 59.00,
                'stock' => 40,
                'id_categoria' => $idLlaveros,
                'imagen_url' => 'https://placehold.co/600x400?text=Llavero+Iniciales',
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
