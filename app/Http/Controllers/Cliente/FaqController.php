<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->orderBy('category')->get();
        $categories = $faqs->pluck('category')->filter()->unique();
        return view('cliente.faq', compact('faqs', 'categories'));
    }
}
