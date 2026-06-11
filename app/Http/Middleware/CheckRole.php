<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php
//
// CAMBIOS:
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

        if ($tipo === 'postulante') {
            $sujeto    = auth('api_postulante')->user();
            $rolSujeto = 'POSTULANTE';
        } else {
            $sujeto    = auth('api')->user();
            $rolSujeto = $sujeto?->rol?->txt_nombre;
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