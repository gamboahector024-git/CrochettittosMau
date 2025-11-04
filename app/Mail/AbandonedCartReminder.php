<?php

namespace App\Mail;

use App\Models\Carrito;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonedCartReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Carrito $carrito;

    public function __construct(Carrito $carrito)
    {
        $this->carrito = $carrito;
        $this->subject('Tienes productos esperando en tu carrito 🧶');
    }

    public function build()
    {
        return $this->view('emails.abandoned_cart_reminder', [
            'carrito' => $this->carrito,
            'usuario' => $this->carrito->usuario,
            'detalles' => $this->carrito->detalles,
        ]);
    }
}
