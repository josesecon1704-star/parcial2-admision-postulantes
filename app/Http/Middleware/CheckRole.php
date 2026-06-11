<?php

namespace App\Http\Middleware;

// DESTINO: app/Http/Middleware/CheckRole.php
//
// CAMBIOS:
//   - Soporta el rol virtual 'POSTULANTE' para sujetos cuyo
//     token JWT tiene el claim 'tipo' === 'postulante'
//     (modelo Postulante, no está en tbl_usuario / tbl_rol).
//   - Para personal administrativo, sigue funcionando igual:
//     usa $usuario->rol->txt_nombre (tbl_rol).

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckRole
{
    /**
     * Uso en rutas:
     *   ->middleware('role:ADMINISTRADOR')
     *   ->middleware('role:ADMINISTRADOR,SECRETARIA')   // cualquiera de los dos
     *   ->middleware('role:POSTULANTE')                  // solo postulantes
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $sujeto = auth('api')->user();

        if (! $sujeto) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], 401);
        }

        // ── Determinar el rol según el tipo de token ────────
        $payload   = JWTAuth::parseToken()->getPayload();
        $tipo      = $payload->get('tipo', 'administrativo');

        $rolSujeto = $tipo === 'postulante'
            ? 'POSTULANTE'
            : ($sujeto->rol?->txt_nombre);

        // Verificar si el rol del sujeto está entre los permitidos
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