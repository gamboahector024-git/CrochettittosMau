<?php

namespace App\Mail;

use App\Models\Peticion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NuevaPeticion extends Mailable
{
    use Queueable, SerializesModels;

    public Peticion $peticion;

    public function __construct(Peticion $peticion)
    {
        $this->peticion = $peticion;
    }

    public function build()
    {
        return $this->subject('Nueva petición recibida — #' . $this->peticion->id_peticion)
            ->view('emails.nueva_peticion', [
                'peticion' => $this->peticion,
                'usuario' => $this->peticion->usuario,
            ]);
    }
}
