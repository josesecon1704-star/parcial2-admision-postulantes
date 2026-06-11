<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO TEMPORAL: app/Http/Controllers/Api/V1/DiagnosticoController.php
// (BORRAR este archivo y su ruta una vez resuelto el problema)
//
// Endpoint público de diagnóstico para verificar que Railway
// está usando la config/auth.php correcta (con guard
// 'api_postulante' y provider 'postulantes').
// ============================================================

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

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
}