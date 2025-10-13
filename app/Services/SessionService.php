<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionService
{
    // Verifica expiración de sesión
    public static function checkSession(Request $request)
    {
        $usuario = $request->session()->get('usuario');
        $expira = $request->session()->get('usuario_expira');

        if (!$usuario || !$expira || Carbon::now()->greaterThan(Carbon::parse($expira))) {
            $request->session()->forget(['usuario', 'usuario_expira']);
            session()->flash('error', 'Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.');
            return false;
        }

        return true;
    }
}