<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php
//
// CAMBIOS:
//   - Ya NO vuelve a llamar a JWTAuth::parseToken() ni a
//     auth($guard)->authenticate()/id(). Esa segunda llamada al
//     guard, dentro de un segundo middleware, lanzaba
//     UserNotFoundException sin capturar (visible como
//     {"message":"User not found"} con 401), aunque el primer
//     middleware (JwtMiddleware) ya había autenticado correctamente.
//   - En su lugar, reutiliza $request->attributes 'auth_tipo' y
//     'auth_sujeto' que JwtMiddleware deja seteados tras autenticar
//     con éxito. JwtMiddleware SIEMPRE corre antes (está primero
//     en el grupo de middleware de las rutas protegidas).
//
// Uso en rutas (sin cambios):
//   ->middleware('role:ADMINISTRADOR')
//   ->middleware('role:ADMINISTRADOR,SECRETARIA')
//   ->middleware('role:POSTULANTE')

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $tipo   = $request->attributes->get('auth_tipo');
        $sujeto = $request->attributes->get('auth_sujeto');

        if (! $sujeto) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $rolSujeto = $tipo === 'postulante'
            ? 'POSTULANTE'
            : $sujeto->rol?->txt_nombre;

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