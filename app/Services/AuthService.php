<?php

namespace App\Services;

//   - Personal administrativo (tbl_usuario) sigue usando
//     tymon/jwt-auth + guard 'api' (Authenticatable real), sin cambios.
//   - Postulante (tbl_postulante)
//
//   El token de postulante lleva los claims:
//     sub  = id_postulante
//     tipo = 'postulante'
//     iat, exp (1 hora, igual que JWT_TTL)
//
//   PostulanteJwtMiddleware decodifica este token manualmente.

use App\Models\Postulante;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;
use DateTimeImmutable;
use DateTimeZone;

class AuthService
{
    /**
     * Intentar login: valida credenciales y devuelve token.
     *
     * Devuelve:
     *   - tipo = 'administrativo' → ['tipo','token','usuario']  (token = string JWT de tymon)
     *   - tipo = 'postulante'     → ['tipo','token','postulante'] (token = string JWT propio, lcobucci)
     *   - null si no hay match en ninguna tabla
     */
    public function login(string $email, string $password): ?array
    {
        // ────────────────────────────────────────────────
        // 1) Personal administrativo: tbl_usuario (sin cambios)
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
        //    Contraseña = su CI (txt_ci), comparación directa.
        //    Token propio firmado con lcobucci/jwt (SIN guards).
        // ────────────────────────────────────────────────
        $postulante = Postulante::where('txt_correo', $email)->first();

        if ($postulante && $this->ciCoincide($postulante->txt_ci, $password)) {
            $token = $this->generarTokenPostulante($postulante);

            return [
                'tipo'       => 'postulante',
                'token'      => $token,
                'postulante' => $postulante,
            ];
        }

        return null;
    }

    /**
     * Genera un JWT simple para el postulante, firmado con JWT_SECRET
     * usando lcobucci/jwt directamente (sin pasar por guards de Laravel).
     *
     * Claims:
     *   sub  = id_postulante (string)
     *   tipo = 'postulante'
     *   iat  = ahora
     *   exp  = ahora + JWT_TTL minutos
     */
    public function generarTokenPostulante(Postulante $postulante): string
    {
        $config = $this->jwtConfig();
        $now    = new DateTimeImmutable();
        $ttlMin = (int) config('jwt.ttl', 60);

        $token = $config->builder()
            ->issuedAt($now)
            ->expiresAt($now->modify("+{$ttlMin} minutes"))
            ->relatedTo((string) $postulante->id_postulante)
            ->withClaim('tipo', 'postulante')
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }

    /**
     * Decodifica y valida un token de postulante.
     * Devuelve el id_postulante (int) o null si es inválido/expirado.
     */
    public function validarTokenPostulante(string $tokenString): ?int
    {
        $config = $this->jwtConfig();

        try {
            $token = $config->parser()->parse($tokenString);
        } catch (\Throwable) {
            return null;
        }

        $constraints = [
            new SignedWith($config->signer(), $config->signingKey()),
            new ValidAt(SystemClock::fromUTC()),
        ];

        if (! $config->validator()->validate($token, ...$constraints)) {
            return null;
        }

        /** @var \Lcobucci\JWT\Token\Plain $token */
        $claims = $token->claims();

        if ($claims->get('tipo') !== 'postulante') {
            return null;
        }

        $sub = $claims->get('sub');
        return $sub !== null ? (int) $sub : null;
    }

    /**
     * Configuración compartida de lcobucci/jwt usando JWT_SECRET.
     */
    private function jwtConfig(): Configuration
    {
        $secret = config('jwt.secret');

        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($secret)
        );
    }

    /**
     * Compara el CI del postulante con la contraseña ingresada.
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
     * Invalidar el token actual (logout) — solo aplica a tymon (administrativo).
     * El portal de postulante simplemente descarta el token en el cliente.
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Renovar el token antes de que expire (solo administrativo).
     *
     * @throws TokenExpiredException|TokenInvalidException
     */
    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    /**
     * Devolver el usuario autenticado actualmente (solo administrativo,
     * usado por AuthController::me() en /api/v1/auth/me).
     */
    public function me(): array
    {
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