<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $primaryKey = 'id_pedido';
    protected $table = 'pedidos';
    
    protected $fillable = [
        'id_usuario',
        'total',
        'estado', 
        'direccion_envio'
    ];

    // Relación con Usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Relación con DetallesPedido
    public function detalles(): HasMany 
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    // Relación con Productos (a través de DetallePedido)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalles_pedido', 'id_pedido', 'id_producto')
            ->withPivot(['cantidad', 'precio_unitario']);
    }
}