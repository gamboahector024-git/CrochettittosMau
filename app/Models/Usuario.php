<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // Nombre de la tabla
    protected $primaryKey = 'id_usuario'; // Clave primaria
    public $timestamps = false; // No tenemos created_at / updated_at

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password_hash',
        'direccion',
        'telefono',
        'rol',
        'fecha_registro'
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_usuario';
    }

    // Estado activo sin depender de columnas específicas
    public function isActive(): bool
    {
        return true;
    }
}
