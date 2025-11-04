<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoDetalle extends Model
{
    protected $primaryKey = 'id_detalle';
    protected $table = 'carrito_detalles';
    
    protected $fillable = [
        'id_carrito',
        'id_producto',
        'cantidad'
    ];

    protected $touches = ['carrito'];

    public function getRouteKeyName()
    {
        return 'id_detalle';
    }

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class, 'id_carrito', 'id_carrito');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}