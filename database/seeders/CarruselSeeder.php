<?php

namespace Database\Seeders;

use App\Models\Carrusel;
use Illuminate\Database\Seeder;

class CarruselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrusel::create([
            'imagen' => 'uploads/1760408705_flores.jpg',
            'orden' => 0,
        ]);

        Carrusel::create([
            'imagen' => 'uploads/1760408761_kuchau.jpg',
            'orden' => 1,
        ]);

        Carrusel::create([
            'imagen' => 'uploads/1760375298_430e181df3dcec1d655c551a593d790a.webp',
            'orden' => 2,
        ]);
    }
}
