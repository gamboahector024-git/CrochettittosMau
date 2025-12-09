<?php

namespace App\Mail;

use App\Models\Peticion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PeticionPropuesta extends Mailable
{
    use Queueable, SerializesModels;

    public Peticion $peticion;
    public $usuario;

    public function __construct(Peticion $peticion)
    {
        $this->peticion = $peticion;
        $this->usuario = $peticion->usuario;
    }

    public function build()
    {
        return $this->subject('Propuesta para tu petición — #' . $this->peticion->id_peticion)
            ->view('emails.peticion_propuesta', [
                'peticion' => $this->peticion,
                'usuario' => $this->usuario,
            ]);
    }
}
