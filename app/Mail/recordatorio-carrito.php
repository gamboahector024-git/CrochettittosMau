<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioCarrito extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $productos;

    public function __construct($usuario, $productos)
    {
        $this->usuario = $usuario;
        $this->productos = $productos;
    }

    public function build()
    {
        return $this->subject('Tienes productos pendientes por comprar 🛒')
                    ->view('emails.recordatorio_carrito');
    }
}

