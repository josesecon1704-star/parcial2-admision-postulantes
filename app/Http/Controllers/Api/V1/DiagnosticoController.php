<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO TEMPORAL: app/Http/Controllers/Api/V1/DiagnosticoController.php
// (BORRAR este archivo y sus rutas una vez resuelto el problema)
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Postulante;
use Illuminate\Http\JsonResponse;
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

    /**
     * Recibe el token vía header Authorization: Bearer {token}
     * y prueba authenticate() contra ambos guards, capturando
     * cualquier excepción para ver el error real.
     */
    public function checkToken(): JsonResponse
    {
        $resultado = [
            'payload' => null,
            'guard_api_postulante' => null,
            'guard_api'            => null,
        ];

        // 1. Leer el payload crudo del token
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $resultado['payload'] = $payload->toArray();
        } catch (\Throwable $e) {
            $resultado['payload_error'] = get_class($e) . ': ' . $e->getMessage();
        }

        // 2. Probar authenticate() con guard api_postulante
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

        // 3. Probar authenticate() con guard api
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
}