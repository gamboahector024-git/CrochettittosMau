<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Muestra el formulario de login
    public function mostrarLogin()
    {
        return view('login');
    }

    // Procesa el login
    public function procesarLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Busca al usuario por email
        $usuario = Usuario::where('email', $request->email)->first();

        // Verifica contraseña
        if ($usuario && Hash::check($request->password, $usuario->password_hash)) {

            // Loguea al usuario usando el guard 'web'
            auth('web')->login($usuario);

            // Redirige según rol
            return $usuario->rol === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('tienda');
        }

        return back()->with('error', 'Email o contraseña incorrectos.');
    }

    // Cierra la sesión
    public function logout()
    {
        auth('web')->logout();
        return redirect()->route('login.form')->with('success', 'Sesión cerrada correctamente.');
    }

    // Muestra el formulario de registro
    public function mostrarRegistro()
    {
        return view('registro');
    }

    // Procesa el registro
    public function procesarRegistro(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password_hash' => \Illuminate\Support\Facades\Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'rol' => 'cliente', // por defecto
        ]);

        return redirect()->route('login.form')->with('success', 'Registro exitoso. Inicia sesión.');
    }
}
