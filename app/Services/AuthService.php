<?php

namespace App\Services;

// DESTINO: app/Services/AuthService.php
//
// CAMBIOS:
//   - login() ahora soporta DOS tipos de usuario:
//       1) Personal administrativo (tbl_usuario): admin/secretaria/docente
//          → email + password (hash) contra tbl_usuario
//       2) Postulante (tbl_postulante): NO está en tbl_usuario
//          → email (txt_correo) + password = txt_ci (texto plano)
//   - El array devuelto incluye 'tipo' => 'administrativo' | 'postulante'
//     para que AuthController arme la respuesta correcta y el frontend
//     (login.blade.php) sepa a qué vista redirigir.

use App\Models\Postulante;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthService
{
    /**
     * Intentar login: valida credenciales y devuelve token JWT.
     *
     * Devuelve:
     *   - tipo = 'administrativo' → ['tipo','token','usuario']
     *   - tipo = 'postulante'     → ['tipo','token','postulante']
     *   - null si no hay match en ninguna tabla
     *
     * @return array{tipo: string, token: string, usuario?: Usuario, postulante?: Postulante}|null
     */
    public function login(string $email, string $password): ?array
    {
        // ────────────────────────────────────────────────
        // 1) Personal administrativo: tbl_usuario
        // ────────────────────────────────────────────────
        $usuario = Usuario::where('txt_email', $email)
                          ->where('bol_estado', true)
                          ->first();

        if ($usuario && Hash::check($password, $usuario->txt_password)) {
            $token = JWTAuth::fromUser($usuario);

            $usuario->update(['fch_ultimo_acceso' => now()]);

            return [
                'tipo'    => 'administrativo',
                'token'   => $token,
                'usuario' => $usuario->load('rol'),
            ];
        }

        // ────────────────────────────────────────────────
        // 2) Postulante: tbl_postulante
        //    NO tiene cuenta en tbl_usuario.
        //    Contraseña = su CI (txt_ci), comparación directa.
        // ────────────────────────────────────────────────
        $postulante = Postulante::where('txt_correo', $email)->first();

        if ($postulante && $this->ciCoincide($postulante->txt_ci, $password)) {
            $token = JWTAuth::fromUser($postulante);

            return [
                'tipo'       => 'postulante',
                'token'      => $token,
                'postulante' => $postulante,
            ];
        }

        return null;
    }

    /**
     * Compara el CI del postulante con la contraseña ingresada.
     * Normaliza espacios y mayúsculas/minúsculas para evitar
     * fallos por formato (ej: "8765432 SC" vs "8765432sc").
     */
    private function ciCoincide(?string $ciAlmacenado, string $password): bool
    {
        if (! $ciAlmacenado) {
            return false;
        }

        $normalizar = fn(string $v): string => strtoupper(preg_replace('/\s+/', '', $v));

        return $normalizar($ciAlmacenado) === $normalizar($password);
    }

    /**
     * Invalidar el token actual (logout).
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Renovar el token antes de que expire.
     *
     * @throws TokenExpiredException|TokenInvalidException
     */
    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    /**
     * Devolver el usuario o postulante autenticado actualmente.
     * Distingue por el claim 'tipo' del token.
     *
     * @return array{tipo: string, data: Usuario|Postulante}
     */
    public function me(): array
    {
        $payload = JWTAuth::parseToken()->getPayload();
        $tipo    = $payload->get('tipo', 'administrativo');

        if ($tipo === 'postulante') {
            $postulante = Postulante::find(auth('api_postulante')->id());
            return ['tipo' => 'postulante', 'data' => $postulante];
        }

        $usuario = Usuario::find(auth('api')->id());
        return ['tipo' => 'administrativo', 'data' => $usuario->load('rol')];
    }

    /**
     * Formatear la respuesta del token para el cliente.
     */
    public function buildTokenResponse(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => config('jwt.ttl') * 60, // segundos
        ];
    }
}