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
}