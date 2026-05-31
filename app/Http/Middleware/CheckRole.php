<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Uso en rutas:
     *   ->middleware('role:ADMINISTRADOR')
     *   ->middleware('role:ADMINISTRADOR,DOCENTE')   // cualquiera de los dos
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = auth()->user();

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $rolUsuario = $usuario->rol?->txt_nombre; 

        // Verificar si el rol del usuario está entre los permitidos
        if (! in_array($rolUsuario, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para acceder a este recurso.',
                'tu_rol'  => $rolUsuario,
            ], 403);
        }

        return $next($request);
    }
}
