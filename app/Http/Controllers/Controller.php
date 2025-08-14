<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class Controller
{
    public function index(Request $request)
    {   
        $usuarioId = $request->session()->get('usuario');
        if (!$usuarioId) {
            return redirect()->route('login'); 
        }

        $usuario = Usuario::find($usuarioId);
        return view('baseTemplate', compact('usuario'));
    }
}
