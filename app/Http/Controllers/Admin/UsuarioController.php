<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(StoreUsuarioRequest $request)
    {
        $data = $request->validated();
        $data['password_hash'] = Hash::make($request->password);
        
        Usuario::create($data);
        
        Session::flash('success', 'Usuario creado correctamente');
        return redirect()->route('admin.usuarios.index');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(UpdateUsuarioRequest $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $data = $request->validated();
        
        if(!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }
        
        $usuario->update($data);
        
        Session::flash('success', 'Usuario actualizado correctamente');
        return redirect()->route('admin.usuarios.index');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();
        
        Session::flash('success', 'Usuario eliminado correctamente');
        return redirect()->route('admin.usuarios.index');
    }

    /**
     * Cambia el estado de un usuario (activo/inactivo).
     */
    public function toggleStatus(Usuario $usuario)
    {
        // Asume una columna 'status' con valores 'activo' e 'inactivo'
        $usuario->status = ($usuario->status === 'activo') ? 'inactivo' : 'activo';
        $usuario->save();

        Session::flash('success', 'Estado del usuario actualizado correctamente.');
        return back();
    }

    /**
     * Elimina múltiples usuarios en lote.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:usuarios,id_usuario' // Valida que cada ID exista
        ]);

        Usuario::destroy($request->ids);

        Session::flash('success', 'Usuarios seleccionados eliminados correctamente.');
        return back();
    }
}