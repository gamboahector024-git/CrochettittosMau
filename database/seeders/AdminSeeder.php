<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'nombre' => 'Administrador',
            'apellido' => 'Principal',
            'email' => 'admin@correo.com',
            'password_hash' => Hash::make('123'), // contraseña segura
            'direccion' => 'Oficina central',
            'telefono' => '555-1234',
            'rol' => 'admin',
            'fecha_registro' => now(),
        ]);
    }
}
