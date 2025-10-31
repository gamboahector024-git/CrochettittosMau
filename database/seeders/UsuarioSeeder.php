<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'María',
                'apellido' => 'García López',
                'email' => 'maria.garcia@mail.test',
                'password_hash' => bcrypt('password123'),
                'direccion' => 'Calle Principal 123, Apto 4B',
                'telefono' => '555-1001',
                'rol' => 'cliente',
                'fecha_registro' => now()->subDays(30),
            ],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez Martínez',
                'email' => 'carlos.rodriguez@mail.test',
                'password_hash' => bcrypt('password123'),
                'direccion' => 'Avenida Central 456, Piso 2',
                'telefono' => '555-1002',
                'rol' => 'cliente',
                'fecha_registro' => now()->subDays(15),
            ],
            [
                'nombre' => 'Ana',
                'apellido' => 'Fernández Sánchez',
                'email' => 'ana.fernandez@mail.test',
                'password_hash' => bcrypt('password123'),
                'direccion' => 'Calle Secundaria 789, Casa 10',
                'telefono' => '555-1003',
                'rol' => 'cliente',
                'fecha_registro' => now()->subDays(5),
            ],
        ];

        // Evitar duplicados por email
        foreach ($usuarios as $u) {
            $exists = DB::table('usuarios')->where('email', $u['email'])->exists();
            if (!$exists) {
                DB::table('usuarios')->insert($u);
            }
        }
    }
}
