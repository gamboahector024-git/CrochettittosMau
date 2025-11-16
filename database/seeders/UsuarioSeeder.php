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
                'nombre' => 'Sofía',
                'apellido' => 'Hernández Mendoza',
                'email' => 'sofia.hm@example.com',
                'password_hash' => bcrypt('Crochet123!'),
                'direccion' => 'Av. Revolución 1500, Col. San Pedro',
                'telefono' => '555-123-4567',
                'rol' => 'cliente',
                'fecha_registro' => now()->subDays(90),
            ],
            [
                'nombre' => 'Diego',
                'apellido' => 'García Ruiz',
                'email' => 'diego.gr@example.com',
                'password_hash' => bcrypt('Crochet123!'),
                'direccion' => 'Calle Morelos 45, Col. Centro',
                'telefono' => '555-987-6543',
                'rol' => 'cliente',
                'fecha_registro' => now()->subDays(60),
            ],
            [
                'nombre' => 'Valeria',
                'apellido' => 'López Castro',
                'email' => 'valeria.lc@example.com',
                'password_hash' => bcrypt('Crochet123!'),
                'direccion' => 'Paseo de la Reforma 222, Col. Juárez',
                'telefono' => '555-456-7890',
                'rol' => 'cliente',
                'fecha_registro' => now()->subDays(30),
            ],
            [
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'email' => 'admin@crochettittos.com',
                'password_hash' => bcrypt('AdminSecure123!'),
                'direccion' => 'Oficinas Centrales',
                'telefono' => '555-000-1111',
                'rol' => 'admin',
                'fecha_registro' => now()->subDays(120),
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
