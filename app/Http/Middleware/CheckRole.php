<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php
//
// CAMBIOS:
//   - auth($guard)->user() puede lanzar UserNotFoundException
//     (mensaje "User not found") si el JWTGuard intenta re-resolver
//     el usuario y algo falla. Se envuelve en try/catch para evitar
//     que esa excepción se propague como error genérico sin manejar.
//   - Lee el claim 'tipo' del token para elegir el guard correcto:
//       'postulante'    -> auth('api_postulante')->user()  (rol virtual POSTULANTE)
//       administrativo  -> auth('api')->user() + tbl_rol (igual que antes)
//
// Uso en rutas (sin cambios):
//   ->middleware('role:ADMINISTRADOR')
//   ->middleware('role:ADMINISTRADOR,SECRETARIA')
//   ->middleware('role:POSTULANTE')

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $tipo    = $payload->get('tipo', 'administrativo');

        try {
            if ($tipo === 'postulante') {
                $id        = auth('api_postulante')->id();
                $sujeto    = $id ? \App\Models\Postulante::find($id) : null;
                $rolSujeto = 'POSTULANTE';
            } else {
                $id        = auth('api')->id();
                $sujeto    = $id ? \App\Models\Usuario::with('rol')->find($id) : null;
                $rolSujeto = $sujeto?->rol?->txt_nombre;
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
                'debug'   => $e->getMessage(),
            ], 401);
        }

        if (! $sujeto) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

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