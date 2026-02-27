<?php

namespace App\Helpers;

use App\Models\Usuario;

class RoleGate
{
    public static function requireRole(string $rol)
    {
        $userId = session('usuario');

        if (!$userId) {
            abort(403, 'Sesión no válida');
        }

        $usuario = Usuario::find($userId);

        if (!$usuario) {
            abort(403, 'Usuario no encontrado');
        }

        if ($usuario->rol !== $rol) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public static function requireAdmin()
    {
        self::requireRole('administrador');
    }

    public static function requireAcademia()
    {
        self::requireRole('academia');
    }
}
