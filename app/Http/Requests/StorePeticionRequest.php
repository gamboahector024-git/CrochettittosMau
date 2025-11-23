<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeticionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // El middleware de autenticación ya protege esta ruta
        return auth('web')->check();
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer|min:1|max:100',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'imagen_referencia' => 'nullable|image|max:2048',
            'calle' => 'required|string|max:255',
            'colonia' => 'required|string|max:255',
            'municipio_ciudad' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'estado_direccion' => 'required|string|max:100',
        ];
    }
}
