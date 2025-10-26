<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peticion extends Model
{
    use HasFactory;

    protected $table = 'peticiones';
    protected $primaryKey = 'id_peticion';
    
    protected $fillable = [
        'id_usuario',
        'titulo',
        'descripcion',
        'imagen_referencia',
        'estado',
        'respuesta_admin'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
