<?php

namespace App\Http\Middleware;

// ============================================================
// DESTINO: app/Http/Middleware/JwtMiddleware.php
//
// Verifica el token JWT.
//   - Si el token es de personal administrativo (tbl_usuario),
//     además revisa bol_estado.
//   - Si el token es de un postulante (claim 'tipo' === 'postulante'),
//     tbl_postulante NO tiene bol_estado, así que se omite esa
//     verificación.
// ============================================================

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $tipo    = $payload->get('tipo', 'administrativo');

            $sujeto = JWTAuth::parseToken()->authenticate();

            if (! $sujeto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.',
                ], 404);
            }

            // Verificar bol_estado SOLO para personal administrativo
            // (tbl_postulante no tiene esa columna)
            if ($tipo === 'administrativo' && ! $sujeto->bol_estado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ], 403);
            }

        } catch (TokenExpiredException) {
            return response()->json([
                'success' => false,
                'message' => 'El token ha expirado. Inicia sesión nuevamente.',
                'code'    => 'TOKEN_EXPIRED',
            ], 401);

        } catch (TokenInvalidException) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido.',
                'code'    => 'TOKEN_INVALID',
            ], 401);

        } catch (JWTException) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado. Incluye: Authorization: Bearer {token}',
                'code'    => 'TOKEN_ABSENT',
            ], 401);
        }

        return $next($request);
    }
}