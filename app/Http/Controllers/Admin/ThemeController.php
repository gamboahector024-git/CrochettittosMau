<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function toggle(Request $request)
    {
        $currentTheme = $request->session()->get('theme', 'light');
        $newTheme = $currentTheme === 'light' ? 'dark' : 'light';
        
        $request->session()->put('theme', $newTheme);
        
        return back();
    }
}