<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO TEMPORAL: app/Http/Controllers/Api/V1/DiagnosticoController.php
// (BORRAR este archivo y sus rutas una vez resuelto el problema)
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Postulante;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class DiagnosticoController extends Controller
{
    public function checkAuthConfig(): JsonResponse
    {
        return response()->json([
            'guards_configurados'    => array_keys(config('auth.guards')),
            'providers_configurados' => array_keys(config('auth.providers')),
            'guard_api_postulante'   => config('auth.guards.api_postulante'),
            'provider_postulantes'   => config('auth.providers.postulantes'),
            'config_cached'          => app()->configurationIsCached(),
            'jwt_secret_set'         => ! empty(config('jwt.secret')),
        ]);
    }

    public function checkPostulante(int $id): JsonResponse
    {
        $postulante = Postulante::find($id);

        return response()->json([
            'encontrado'            => $postulante !== null,
            'implements_jwtsubject' => $postulante instanceof JWTSubject,
            'jwt_identifier'        => $postulante?->getJWTIdentifier(),
            'jwt_custom_claims'     => method_exists($postulante, 'getJWTCustomClaims')
                ? $postulante?->getJWTCustomClaims()
                : 'NO_METHOD',
            'tabla'                 => $postulante?->getTable(),
            'primary_key'           => $postulante?->getKeyName(),
        ]);
    }

    public function checkToken(): JsonResponse
    {
        $resultado = [
            'payload' => null,
            'guard_api_postulante' => null,
            'guard_api'            => null,
        ];

        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $resultado['payload'] = $payload->toArray();
        } catch (\Throwable $e) {
            $resultado['payload_error'] = get_class($e) . ': ' . $e->getMessage();
        }

        try {
            /** @var \Tymon\JWTAuth\JWTGuard $guard */
            $guard = auth('api_postulante');
            $sujeto = $guard->authenticate();
            $resultado['guard_api_postulante'] = [
                'success' => true,
                'class'   => $sujeto ? get_class($sujeto) : null,
                'id'      => $sujeto?->getAuthIdentifier(),
            ];
        } catch (\Throwable $e) {
            $resultado['guard_api_postulante'] = [
                'success' => false,
                'error'   => get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        try {
            /** @var \Tymon\JWTAuth\JWTGuard $guard */
            $guard = auth('api');
            $sujeto = $guard->authenticate();
            $resultado['guard_api'] = [
                'success' => true,
                'class'   => $sujeto ? get_class($sujeto) : null,
                'id'      => $sujeto?->getAuthIdentifier(),
            ];
        } catch (\Throwable $e) {
            $resultado['guard_api'] = [
                'success' => false,
                'error'   => get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        return response()->json($resultado);
    }

    /**
     * NUEVO: revisa qué clase está realmente registrada
     * para el alias 'jwt.auth' en el router de Laravel.
     */
    public function checkMiddlewareAlias(Router $router): JsonResponse
    {
        // middlewareGroups y aliases registrados
        $aliases = [];

        try {
            $reflection = new \ReflectionClass($router);
            $property = $reflection->getProperty('middlewarePriority');
            $property->setAccessible(true);
        } catch (\Throwable $e) {
            // ignorar
        }

        // Forma directa: resolver el alias manualmente
        $resolved = null;
        try {
            $resolved = app('router')->getMiddleware()['jwt.auth'] ?? 'NO_REGISTRADO';
        } catch (\Throwable $e) {
            $resolved = 'ERROR: ' . $e->getMessage();
        }

        $resolvedRole = null;
        try {
            $resolvedRole = app('router')->getMiddleware()['role'] ?? 'NO_REGISTRADO';
        } catch (\Throwable $e) {
            $resolvedRole = 'ERROR: ' . $e->getMessage();
        }

        return response()->json([
            'jwt_auth_alias' => $resolved,
            'role_alias'     => $resolvedRole,
            'jwt_middleware_class_exists' => class_exists(\App\Http\Middleware\JwtMiddleware::class),
        ]);
    }

    
}