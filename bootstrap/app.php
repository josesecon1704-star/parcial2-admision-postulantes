<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // ── CAMBIO CLAVE ─────────────────────────────────
            // Antes apuntaba a Tymon\JWTAuth\Http\Middleware\Authenticate,
            // que SIEMPRE usa el guard por defecto ('api' -> tbl_usuario)
            // y por eso fallaba con "User not found" para postulantes
            // (sub apunta a tbl_postulante).
            //
            // Nuestro JwtMiddleware lee el claim 'tipo' del token y
            // autentica con 'api_postulante' o 'api' según corresponda.
            'jwt.auth' => \App\Http\Middleware\JwtMiddleware::class,
            'role'     => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();