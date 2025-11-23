<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Faq::insert([
            [
                'question' => '¿Cuánto tarda mi pedido en llegar?',
                'answer' => 'El tiempo de entrega estándar es de 3 a 7 días hábiles dependiendo de tu ubicación.',
                'category' => 'Envíos',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => '¿Puedo personalizar un producto?',
                'answer' => '¡Sí! Puedes solicitar productos personalizados a través de la sección de peticiones.',
                'category' => 'Personalización',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => '¿Qué métodos de pago aceptan?',
                'answer' => 'Aceptamos tarjetas de crédito, débito y pagos vía PayPal.',
                'category' => 'Pagos',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => '¿Qué hago si mi pedido llega dañado?',
                'answer' => 'Contáctanos de inmediato con fotos del daño y te ayudaremos a resolverlo.',
                'category' => 'Envíos',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
