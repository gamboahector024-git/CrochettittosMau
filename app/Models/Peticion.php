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
        'id_categoria',
        'titulo',
        'descripcion',
        'cantidad',
        'imagen_referencia',
        'calle',
        'colonia',
        'municipio_ciudad',
        'codigo_postal',
        'estado_direccion',
        'estado',
        'respuesta_admin',
        'precio_propuesto',
        'fecha_respuesta_admin',
        'respuesta_cliente',
        'fecha_respuesta_cliente'
    ];

    protected $casts = [
        'fecha_respuesta_admin' => 'datetime',
        '' => 'datetime',
        'precio_propuesto' => 'decimal:2',
        'cantidad' => 'integer'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}
