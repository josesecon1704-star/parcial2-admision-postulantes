<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php
//
// IMPORTANTE: el alias 'jwt.auth' es sobreescrito por
// Tymon\JWTAuth\Providers\LaravelServiceProvider para apuntar a
// Tymon\JWTAuth\Http\Middleware\Authenticate (esto ocurre DESPUÉS
// de bootstrap/app.php y no se puede evitar fácilmente). Por lo
// tanto, NUESTRO App\Http\Middleware\JwtMiddleware NUNCA se
// ejecuta en las rutas de personal administrativo, y
// $request->attributes 'auth_sujeto' nunca queda seteado por él.
//
// Tymon\Authenticate SÍ deja al guard 'api' autenticado
// correctamente (auth('api')->user() funciona). Por eso CheckRole
// resuelve el usuario directamente desde el guard, como en la
// versión original — sin depender de auth_sujeto.
//
// Uso en rutas:
//   ->middleware('role:ADMINISTRADOR')
//   ->middleware('role:ADMINISTRADOR,SECRETARIA')
//   ->middleware('role:DOCENTE')

use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $id = auth('api')->id();

        if (! $id) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        $usuario = Usuario::with('rol')->find($id);

        if (! $usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        if (! $usuario->bol_estado) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ], 403);
        }

        $rolUsuario = $usuario->rol?->txt_nombre;

        if (! in_array($rolUsuario, $roles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para acceder a este recurso.',
                'tu_rol'  => $rolUsuario,
            ], 403);
        }

        // Disponible por si algún controller lo necesita
        $request->attributes->set('auth_tipo', 'administrativo');
        $request->attributes->set('auth_sujeto', $usuario);

        return $next($request);
    }
}