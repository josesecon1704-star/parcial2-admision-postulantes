<?php

namespace App\Http\Middleware;

// ============================================================
// Versión simplificada: SOLO personal administrativo
// (ADMINISTRADOR / SECRETARIA / DOCENTE) vía guard 'api' (tbl_usuario).
//
// El portal del postulante usa un middleware completamente
// independiente: PostulanteJwtMiddleware (alias 'postulante.jwt'),
// que NO depende de este ni de ningún guard de Laravel.
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

            /** @var \App\Models\Usuario $sujeto */
            $sujeto->loadMissing('rol');

            $request->attributes->set('auth_tipo', 'administrativo');
            $request->attributes->set('auth_sujeto', $sujeto);

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