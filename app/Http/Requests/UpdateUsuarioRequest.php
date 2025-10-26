<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('web')->check() && auth('web')->user()->rol === 'admin';
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');

        return [
            'nombre' => 'sometimes|required|string|max:100',
            'apellido' => 'sometimes|required|string|max:100',
            'email' => [
                'sometimes', 'required', 'email', 'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario?->id_usuario, 'id_usuario'),
            ],
            'password' => 'sometimes|nullable|string|min:6|confirmed',
            'direccion' => 'sometimes|nullable|string',
            'telefono' => 'sometimes|nullable|string|max:20',
            'rol' => 'sometimes|required|in:cliente,admin',
        ];
    }
}
