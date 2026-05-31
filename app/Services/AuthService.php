<?php

namespace App\Services;

// DESTINO: app/Services/AuthService.php

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
     * @return array{token: string, usuario: Usuario}|null
     */
    public function login(string $email, string $password): ?array
    {
        // Buscar usuario activo por email
        $usuario = Usuario::where('txt_email', $email)
                          ->where('bol_estado', true)
                          ->first();

        // Verificar existencia y contraseña
        if (! $usuario || ! Hash::check($password, $usuario->txt_password)) {
            return null;
        }

        // Generar token JWT
        $token = JWTAuth::fromUser($usuario);

        // Registrar último acceso
        $usuario->update(['fch_ultimo_acceso' => now()]);

        return [
            'token'   => $token,
            'usuario' => $usuario->load('rol'),
        ];
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
     * Devolver el usuario autenticado actualmente.
     */
    public function me(): Usuario
    {
        return auth()->user()->load('rol');
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
