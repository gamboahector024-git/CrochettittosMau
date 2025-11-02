<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\Producto;

class PerfilController extends Controller
{
    public function index()
    {
        $usuario = Usuario::find(session('id_usuario'));
        $pedidos = Pedido::where('id_usuario', session('id_usuario'))->orderBy('created_at', 'desc')->get();
        
        return view('cliente.perfil', compact('usuario', 'pedidos'));
    }

    public function listaDeseos()
    {
        $usuario = Usuario::with('listaDeseos')->find(session('id_usuario'));
        return view('cliente.lista-deseos', compact('usuario'));
    }

    public function agregarListaDeseos(Request $request, $productoId)
    {
        // Lógica para agregar a lista de deseos
    }

    public function eliminarListaDeseos(Request $request, $productoId)
    {
        // Lógica para eliminar de lista de deseos
    }
}
