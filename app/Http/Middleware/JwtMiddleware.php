<?php

namespace App\Http\Middleware;

// ============================================================
// DESTINO: app/Http/Middleware/JwtMiddleware.php
//
// Verifica el token JWT.
//   - Lee el claim 'tipo' del payload SIN autenticar todavía.
//   - 'tipo' === 'postulante' -> autentica con guard 'api_postulante'
//     (provider Postulante). tbl_postulante NO tiene bol_estado,
//     así que se omite esa verificación.
//   - cualquier otro caso -> autentica con guard 'api' (provider
//     Usuario) y valida bol_estado, como antes.
//
// Tras este middleware, auth('api')->user() o
// auth('api_postulante')->user() devuelven el sujeto autenticado
// según corresponda (CheckRole y los controllers usan el guard
// adecuado).
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

            if ($tipo === 'postulante') {
                /** @var \Tymon\JWTAuth\JWTGuard $guard */
                $guard  = auth('api_postulante');
                $sujeto = $guard->authenticate();

                if (! $sujeto) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Postulante no encontrado.',
                    ], 404);
                }

                // tbl_postulante no tiene bol_estado: no se valida estado.

            } else {
                /** @var \Tymon\JWTAuth\JWTGuard $guard */
                $guard  = auth('api');
                $sujeto = $guard->authenticate();

                if (! $sujeto) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Usuario no encontrado.',
                    ], 404);
                }

                if (! $sujeto->bol_estado) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
                    ], 403);
                }
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