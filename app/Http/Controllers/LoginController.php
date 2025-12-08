<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Muestra el formulario de login
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Procesa el login
    public function procesarLogin(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Busca al usuario por email
        $usuario = Usuario::where('email', $credentials['email'])->first();

        // Verifica contraseña
        if ($usuario && Hash::check($credentials['password'], $usuario->password_hash)) {

            // Loguea al usuario usando el guard 'web'
            auth('web')->login($usuario);

            // Redirige según rol
            if ($usuario->rol === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Para clientes: regresar a la URL que intentaban visitar (carrito/checkout),
            // o a la tienda si no hay una URL previa guardada
            return redirect()->intended(route('tienda'));
        }

        return back()->with('error', 'Email o contraseña incorrectos.');
    }

    // Cierra la sesión
    public function logout(Request $request)
    {
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tienda')->with('success', 'Sesión cerrada correctamente.');
    }

    // Muestra el formulario de registro
    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    // Procesa el registro
    public function procesarRegistro(RegisterRequest $request)
    {
        $data = $request->validated();

        Usuario::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'password_hash' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'telefono' => $data['telefono'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'rol' => 'cliente', // por defecto
        ]);

        return redirect()->route('login.form')->with('success', 'Registro exitoso. Inicia sesión.');
    }
}
