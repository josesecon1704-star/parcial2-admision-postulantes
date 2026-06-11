<?php

// DESTINO: config/auth.php  (REEMPLAZAR el existente completamente)
//
// CAMBIOS:
//   - Se agrega el guard 'api_postulante' (driver jwt) con su propio
//     provider 'postulantes' -> App\Models\Postulante.
//   - El guard 'api' (Usuario) sigue siendo el default para personal
//     administrativo.
//   - JwtMiddleware/CheckRole eligen el guard correcto según el claim
//     'tipo' del token ('postulante' -> api_postulante, resto -> api).

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard'     => 'api',   // JWT como guard por defecto (personal administrativo)
        'passwords' => 'usuarios',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    | 'api'            -> JWT sobre tbl_usuario (admin/secretaria/docente)
    | 'api_postulante' -> JWT sobre tbl_postulante (portal del postulante)
    */
    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'usuarios',
        ],
        'api' => [
            'driver'   => 'jwt',        // tymon/jwt-auth
            'provider' => 'usuarios',
        ],
        'api_postulante' => [
            'driver'   => 'jwt',
            'provider' => 'postulantes',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'usuarios' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Usuario::class,
        ],
        'postulantes' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Postulante::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Configuration
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'usuarios' => [
            'provider' => 'usuarios',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];