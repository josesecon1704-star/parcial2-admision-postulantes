<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php
//
// Versión simplificada: solo personal administrativo.
// Reutiliza $request->attributes 'auth_sujeto' que JwtMiddleware
// deja seteado tras autenticar con éxito (sin volver a tocar el
// guard JWT).
//
// Uso en rutas:
//   ->middleware('role:ADMINISTRADOR')
//   ->middleware('role:ADMINISTRADOR,SECRETARIA')
//   ->middleware('role:DOCENTE')

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $sujeto = $request->attributes->get('auth_sujeto');

        if (! $sujeto) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $rolSujeto = $sujeto->rol?->txt_nombre;

        if (! in_array($rolSujeto, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para acceder a este recurso.',
                'tu_rol'  => $rolSujeto,
            ], 403);
        }

        return $next($request);
    }
}