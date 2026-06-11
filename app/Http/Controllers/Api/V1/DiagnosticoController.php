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
}
