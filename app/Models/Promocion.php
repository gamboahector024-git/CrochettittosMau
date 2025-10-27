<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';
    protected $primaryKey = 'id_promocion';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'valor',
        'id_producto',
        'fecha_inicio',
        'fecha_fin',
        'activa',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_promocion';
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
