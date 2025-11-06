<?php

namespace App\Mail;

use App\Models\Carrito;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioCarrito extends Mailable
{
    use Queueable, SerializesModels;

    public Carrito $carrito;
    public $productos;
    public $usuario;

    public function __construct(Carrito $carrito, $productos)
    {
        $this->carrito = $carrito;
        $this->productos = $productos;
        $this->usuario = $carrito->usuario;
    }

    public function build()
    {
        return $this->subject('Tienes productos pendientes por comprar 🛒')
            ->view('emails.recordatorio_carrito', [
                'usuario' => $this->usuario,
                'productos' => $this->productos,
            ]);
    }
}
