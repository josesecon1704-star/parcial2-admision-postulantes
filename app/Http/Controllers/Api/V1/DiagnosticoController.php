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
     * NUEVO: lee bootstrap/app.php DIRECTAMENTE DEL DISCO en runtime
     * y muestra su contenido + hash, para confirmar que el archivo
     * desplegado en el contenedor es el correcto (sin opcache/caché
     * de por medio).
     */
    public function checkBootstrapFile(): JsonResponse
    {
        $path = base_path('bootstrap/app.php');

        $existe = file_exists($path);
        $contenido = $existe ? file_get_contents($path) : null;

        return response()->json([
            'path'                 => $path,
            'existe'               => $existe,
            'md5'                  => $contenido ? md5($contenido) : null,
            'contiene_jwtmiddleware' => $contenido ? str_contains($contenido, 'JwtMiddleware') : null,
            'contiene_tymon_authenticate' => $contenido ? str_contains($contenido, 'Tymon\\JWTAuth\\Http\\Middleware\\Authenticate') : null,
            'contenido_completo'   => $contenido,
            'opcache_enabled'      => function_exists('opcache_get_status') ? (opcache_get_status(false) !== false) : 'opcache_no_disponible',
        ]);
    }

    /**
     * NUEVO: lista las rutas registradas para 'postulante/me'
     * y muestra qué middlewares tiene aplicados EN RUNTIME.
     */
    public function checkRouteMiddleware(): JsonResponse
    {
        $routes = collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->filter(fn($r) => str_contains($r->uri(), 'postulante/me'))
            ->map(fn($r) => [
                'uri'        => $r->uri(),
                'methods'    => $r->methods(),
                'middleware' => $r->gatherMiddleware(),
            ])
            ->values();

        return response()->json($routes);
    }

    public function checkMeFlow(): JsonResponse
    {
        $resultado = [];

        try {
            $id = auth('api_postulante')->id();
            $resultado['auth_id'] = $id;
        } catch (\Throwable $e) {
            $resultado['auth_id_error'] = get_class($e) . ': ' . $e->getMessage();
            return response()->json($resultado);
        }

        try {
            $postulante = Postulante::find($resultado['auth_id']);
            $resultado['postulante_encontrado'] = $postulante !== null;
        } catch (\Throwable $e) {
            $resultado['postulante_error'] = get_class($e) . ': ' . $e->getMessage();
            return response()->json($resultado);
        }

        if (! $postulante) {
            $resultado['mensaje'] = 'postulante es null';
            return response()->json($resultado);
        }

        try {
            $service = app(\App\Services\PostulanteService::class);
            $data = $service->formatear($postulante, conRelaciones: true);
            $resultado['formatear_ok'] = true;
            $resultado['data'] = $data;
        } catch (\Throwable $e) {
            $resultado['formatear_error'] = get_class($e) . ': ' . $e->getMessage();
            $resultado['formatear_trace'] = collect($e->getTrace())->take(5)->map(fn($t) => ($t['class'] ?? '') . ($t['type'] ?? '') . ($t['function'] ?? '') . ' @ ' . ($t['file'] ?? '') . ':' . ($t['line'] ?? ''))->all();
        }

        return response()->json($resultado);
    }


    public function checkDoubleAuth(): JsonResponse
    {
        $resultado = [];

        try {
            /** @var \Tymon\JWTAuth\JWTGuard $guard1 */
            $guard1 = auth('api_postulante');
            $first = $guard1->authenticate();
            $resultado['first_call'] = [
                'success' => true,
                'class'   => get_class($first),
            ];
        } catch (\Throwable $e) {
            $resultado['first_call'] = [
                'success' => false,
                'error'   => get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        try {
            /** @var \Tymon\JWTAuth\JWTGuard $guard2 */
            $guard2 = auth('api_postulante');
            $second = $guard2->authenticate();
            $resultado['second_call'] = [
                'success' => true,
                'class'   => get_class($second),
            ];
        } catch (\Throwable $e) {
            $resultado['second_call'] = [
                'success' => false,
                'error'   => get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        // Simular: primero 'api' (default), luego 'api_postulante'
        try {
            /** @var \Tymon\JWTAuth\JWTGuard $guardApi */
            $guardApi = auth('api');
            $resultApi = $guardApi->authenticate();
            $resultado['api_then_postulante']['api'] = [
                'success' => true,
                'class'   => get_class($resultApi),
            ];
        } catch (\Throwable $e) {
            $resultado['api_then_postulante']['api'] = [
                'success' => false,
                'error'   => get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        try {
            /** @var \Tymon\JWTAuth\JWTGuard $guardPost */
            $guardPost = auth('api_postulante');
            $resultPost = $guardPost->authenticate();
            $resultado['api_then_postulante']['postulante'] = [
                'success' => true,
                'class'   => get_class($resultPost),
            ];
        } catch (\Throwable $e) {
            $resultado['api_then_postulante']['postulante'] = [
                'success' => false,
                'error'   => get_class($e) . ': ' . $e->getMessage(),
            ];
        }

        return response()->json($resultado);
    }

}