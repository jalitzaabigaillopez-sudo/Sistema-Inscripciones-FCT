<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class InicioController extends Controller
{
     /**
     * Este metodo toma el usuario en sesion una vez se han verificado los credenciales
     * Verifica el tipo de usuario y lo redirige a su vista respectiva admin o academia
     */
    public function index(Request $request)
    {   
        $usuarioId = $request->session()->get('usuario');
        if (!$usuarioId) {
            return redirect()->route('login'); 
        }

        $usuario = Usuario::find($usuarioId);

        // Verificar tipo de usuarios
        if($usuario->rol != 'academia'){
            //admin
            return view('admin/dashboard', compact('usuario'));
        }
        //academia
        $academia = $usuario->academia;
        return view('academia/dashboard-academia', compact('usuario', 'academia'));
    }
}
