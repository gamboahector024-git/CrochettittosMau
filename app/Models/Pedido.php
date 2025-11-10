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
        'id_peticion',
        'total',
        'estado', 
        'calle',
        'colonia',
        'municipio_ciudad',
        'codigo_postal',
        'estado_direccion',
        'metodo_pago',
        'empresa_envio',
        'codigo_rastreo',
        'fecha_envio',
        'fecha_entrega_estimada'
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime',
        'fecha_envio' => 'datetime',
        'fecha_entrega_estimada' => 'date'
    ];

    // Relación con Usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // Relación con DetallesPedido
    public function detalles(): HasMany 
    {
        return $this->hasMany(PedidoDetalle::class, 'id_pedido', 'id_pedido');
    }

    // Relación con Productos (a través de DetallePedido)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'pedido_detalles', 'id_pedido', 'id_producto')
            ->withPivot(['cantidad', 'precio_unitario']);
    }

    public function peticion(): BelongsTo
    {
        return $this->belongsTo(Peticion::class, 'id_peticion', 'id_peticion');
    }
}