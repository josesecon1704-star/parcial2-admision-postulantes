<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/AuthController.php
//
// CAMBIOS:
//   - login() ahora distingue 'administrativo' vs 'postulante'
//     según lo que devuelva AuthService::login().
//     El frontend (login.blade.php) usa 'data.tipo' para decidir
//     a dónde redirigir:
//       'administrativo' → /prueba2  (admin3.blade.php)
//       'postulante'      → /portal-postulante (postulante.blade.php)
//   - me() también distingue ambos tipos.
// ============================================================

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    // ──────────────────────────────────────────────────────
    // CU-01: Iniciar sesión
    // POST /api/v1/auth/login
    // Body: { "txt_email": "...", "txt_password": "..." }
    //
    // Para personal administrativo (tbl_usuario): password = hash real
    // Para postulantes (tbl_postulante): password = su CI (txt_ci)
    // ──────────────────────────────────────────────────────
    public function login(LoginRequest $request): JsonResponse
    {
        $resultado = $this->authService->login(
            $request->txt_email,
            $request->txt_password
        );

        if (! $resultado) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas o usuario inactivo.',
            ], 401);
        }

        $token = $this->authService->buildTokenResponse($resultado['token']);

        // ── Personal administrativo ─────────────────────────
        if ($resultado['tipo'] === 'administrativo') {
            $usuario = $resultado['usuario'];

            return response()->json([
                'success' => true,
                'message' => 'Sesión iniciada correctamente.',
                'data'    => [
                    'tipo'    => 'administrativo',
                    'usuario' => [
                        'id'        => $usuario->id_usuario,
                        'username'  => $usuario->txt_username,
                        'email'     => $usuario->txt_email,
                        'rol'       => $usuario->rol->txt_nombre,
                    ],
                    'token' => $token,
                ],
            ], 200);
        }

        // ── Postulante ───────────────────────────────────────
        $postulante = $resultado['postulante'];

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada correctamente.',
            'data'    => [
                'tipo'       => 'postulante',
                'postulante' => [
                    'id_postulante' => $postulante->id_postulante,
                    'txt_nombre'    => $postulante->txt_nombre,
                    'txt_ci'        => $postulante->txt_ci,
                    'txt_correo'    => $postulante->txt_correo,
                ],
                'token' => $token,
            ],
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // CU-02: Cerrar sesión
    // POST /api/v1/auth/logout
    // Header: Authorization: Bearer {token}
    // ──────────────────────────────────────────────────────
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente.',
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo cerrar la sesión. Token inválido.',
            ], 400);
        }
    }

    // ──────────────────────────────────────────────────────
    // Renovar token antes de expirar
    // POST /api/v1/auth/refresh
    // ──────────────────────────────────────────────────────
    public function refresh(): JsonResponse
    {
        try {
            $nuevoToken = $this->authService->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Token renovado.',
                'data'    => $this->authService->buildTokenResponse($nuevoToken),
            ], 200);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El token expiró y no puede renovarse. Inicia sesión nuevamente.',
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido.',
            ], 400);
        }
    }

    // ──────────────────────────────────────────────────────
    // CU-03: Perfil del usuario autenticado
    // GET /api/v1/auth/me
    // Header: Authorization: Bearer {token}
    //
    // Distingue 'administrativo' vs 'postulante' por el claim 'tipo'.
    // ──────────────────────────────────────────────────────
    public function me(): JsonResponse
    {
        $resultado = $this->authService->me();

        if ($resultado['tipo'] === 'postulante') {
            $postulante = $resultado['data'];

            return response()->json([
                'success' => true,
                'data'    => [
                    'tipo'          => 'postulante',
                    'id_postulante' => $postulante->id_postulante,
                    'nombre'        => $postulante->txt_nombre,
                    'email'         => $postulante->txt_correo,
                    'ci'            => $postulante->txt_ci,
                ],
            ], 200);
        }

        $usuario = $resultado['data'];

        return response()->json([
            'success' => true,
            'data'    => [
                'tipo'          => 'administrativo',
                'id'            => $usuario->id_usuario,
                'username'      => $usuario->txt_username,
                'email'         => $usuario->txt_email,
                'rol'           => $usuario->rol->txt_nombre,
                'estado'        => $usuario->bol_estado,
                'ultimo_acceso' => $usuario->fch_ultimo_acceso?->format('d/m/Y H:i:s'),
            ],
        ], 200);
    }
    
}