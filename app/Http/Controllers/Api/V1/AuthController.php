<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/AuthController.php
// Crear carpeta Api/V1/ si no existe.
//
// Respuestas JSON usando nombres de columna reales:
//   txt_username | txt_email | txt_nombre (del rol)
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
    // Body: { "email": "admin@ficct.edu.bo", "password": "..." }
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

        $usuario = $resultado['usuario'];

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada correctamente.',
            'data'    => [
                'usuario' => [
                    'id'        => $usuario->id_usuario,        // PK real
                    'username'  => $usuario->txt_username,      // columna real
                    'email'     => $usuario->txt_email,         // columna real
                    'rol'       => $usuario->rol->txt_nombre,   // columna real de tbl_rol
                ],
                'token' => $this->authService->buildTokenResponse($resultado['token']),
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
    // ──────────────────────────────────────────────────────
    public function me(): JsonResponse
    {
        $usuario = $this->authService->me();

        return response()->json([
            'success' => true,
            'data'    => [
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
