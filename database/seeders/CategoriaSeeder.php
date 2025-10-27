<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Llaveros', 'descripcion' => 'Productos de llaveros personalizados'],
            ['nombre' => 'Flores', 'descripcion' => 'Arreglos florales y decorativos'],
            ['nombre' => 'Personalizados', 'descripcion' => 'Productos hechos a medida'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                $categoria
            );
        }
    }
}
