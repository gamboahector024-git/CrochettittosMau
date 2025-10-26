<?php

namespace App\Http\Controllers\Admin;

use App\Models\Peticion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeticionController extends Controller
{
    // Middleware opcional para admin
    public function __construct()
    {
        $this->middleware('can:admin');
    }

    public function index()
    {
        return Peticion::with('usuario')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'titulo' => 'required|string|max:150',
            'descripcion' => 'required|string',
            'estado' => 'required|in:pendiente,en revisión,aceptada,rechazada,completada'
        ]);

        return Peticion::create($validated);
    }

    // Métodos adicionales para admin
    public function cambiarEstado(Peticion $peticion, Request $request)
    {
        $peticion->update([
            'estado' => $request->estado,
            'respuesta_admin' => $request->respuesta
        ]);
        
        return $peticion;
    }
}
