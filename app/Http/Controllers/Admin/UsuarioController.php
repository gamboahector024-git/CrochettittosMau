<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:8',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string',
            'rol' => 'required|in:admin,cliente'
        ]);

        $data = $request->all();
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'email' => 'required|email|unique:usuarios,email,'.$id.',id_usuario',
            'password' => 'nullable|min:8',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string',
            'rol' => 'required|in:admin,cliente'
        ]);

        $usuario = Usuario::findOrFail($id);
        $data = $request->except('password');
        
        if($request->password) {
            $data['password_hash'] = Hash::make($request->password);
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