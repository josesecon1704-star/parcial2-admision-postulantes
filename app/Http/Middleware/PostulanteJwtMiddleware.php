<?php

namespace App\Http\Middleware;

// ============================================================
// DESTINO: app/Http/Middleware/PostulanteJwtMiddleware.php
// (archivo NUEVO)
//
// Middleware exclusivo para el portal del postulante.
// NO usa guards de Laravel ni tymon/jwt-auth para autenticar —
// decodifica el JWT manualmente con AuthService::validarTokenPostulante()
// (lcobucci/jwt directo) y busca el postulante por su PK.
//
// Esto evita por completo los problemas de "User not found" que
// ocurrían al re-autenticar el mismo token más de una vez con
// guards JWT de Laravel.
//
// Deja el postulante autenticado en:
//   $request->attributes->get('auth_sujeto')  → instancia de Postulante
//   $request->attributes->get('auth_tipo')    → 'postulante'
//
// Uso en rutas:
//   Route::middleware('postulante.jwt')->group(...)
// ============================================================

use App\Models\Postulante;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostulanteJwtMiddleware
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado. Incluye: Authorization: Bearer {token}',
                'code'    => 'TOKEN_ABSENT',
            ], 401);
        }

        $tokenString = trim(substr($header, 7));

        if ($tokenString === '' || $tokenString === 'null' || $tokenString === 'undefined') {
            return response()->json([
                'success' => false,
                'message' => 'Token no proporcionado.',
                'code'    => 'TOKEN_ABSENT',
            ], 401);
        }

        $idPostulante = $this->authService->validarTokenPostulante($tokenString);

        if ($idPostulante === null) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido o expirado. Inicia sesión nuevamente.',
                'code'    => 'TOKEN_INVALID',
            ], 401);
        }

        $postulante = Postulante::find($idPostulante);

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        $request->attributes->set('auth_tipo', 'postulante');
        $request->attributes->set('auth_sujeto', $postulante);

        return $next($request);
    }
}
