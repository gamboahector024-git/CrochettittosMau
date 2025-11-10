<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Promocion;

class Producto extends Model
{
    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'nombre', 
        'descripcion',
        'precio',
        'stock',  
        'id_categoria',
        'imagen_url',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_producto';
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class, 'pedido_detalles', 'id_producto', 'id_pedido')
            ->withPivot(['cantidad', 'precio_unitario']);
    }

    public function promociones(): HasMany
    {
        return $this->hasMany(Promocion::class, 'id_producto', 'id_producto');
    }

    public function promocionActiva(): HasOne
    {
        return $this->hasOne(Promocion::class, 'id_producto', 'id_producto')
            ->where('activa', true)
            ->whereDate('fecha_inicio', '<=', now())
            ->whereDate('fecha_fin', '>=', now())
            ->latest('id_promocion');
    }

    public function ultimaPromocion(): HasOne
    {
        return $this->hasOne(Promocion::class, 'id_producto', 'id_producto')
            ->latest('id_promocion');
    }

    public function promocionActivaFlag(): HasOne
    {
        return $this->hasOne(Promocion::class, 'id_producto', 'id_producto')
            ->where('activa', true)
            ->latest('id_promocion');
    }
}