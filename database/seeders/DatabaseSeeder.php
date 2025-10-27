<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProductoSeeder;
use Database\Seeders\PedidoSeeder;
use Database\Seeders\PeticionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategoriaSeeder::class,
            ProductoSeeder::class,
            PedidoSeeder::class,
            PeticionSeeder::class,
        ]);
    }
}
