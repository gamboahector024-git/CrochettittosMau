<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Http\Requests\UpdateProfileRequest; // Punto 4 - Form Request

class PerfilController extends Controller
{
    // Obtener usuario autenticado
    protected function getAuthenticatedUser()
    {
        return Usuario::findOrFail(auth()->id());
    }

    // Vista principal del perfil
    public function index()
    {
        $usuario = $this->getAuthenticatedUser();
        $pedidos = Pedido::with('productos')
                    ->where('id_usuario', $usuario->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('cliente.perfil', [
            'usuario' => $usuario,
            'pedidos' => $pedidos
        ]);
    }

    // Formulario de edición
    public function edit()
    {
        $usuario = $this->getAuthenticatedUser();
        return view('cliente.perfil-edit', compact('usuario'));
    }

    // Actualizar perfil (usando Form Request)
    public function update(UpdateProfileRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Solo actualizar contraseña si se proporcionó
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password_hash'] = bcrypt($data['password']);
                unset($data['password']);
            }

            $this->getAuthenticatedUser()->update($data);
            
            return redirect()->route('perfil.index')
                ->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: '.$e->getMessage());
        }
    }
}