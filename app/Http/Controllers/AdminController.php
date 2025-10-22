<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() { return view('admin.dashboard'); }
    public function usuarios() { return view('admin.usuarios'); }
    public function productos() { return view('admin.productos'); }
    public function pedidos() { return view('admin.pedidos'); }
}
