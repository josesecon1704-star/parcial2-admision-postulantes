<?php

// DESTINO: bootstrap/app.php (REEMPLAZAR)
//
// CAMBIO: se agrega el alias 'postulante.jwt' apuntando al
// nuevo PostulanteJwtMiddleware (login/validación manual con
// lcobucci/jwt, sin guards de Laravel).

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
        $middleware->validateCsrfTokens(except: [
            'api/v1/stripe/webhook',
        ]);

        $middleware->alias([
            // Personal administrativo (admin/secretaria/docente) — tymon/jwt-auth + guard 'api'
            'jwt.auth' => \App\Http\Middleware\JwtMiddleware::class,
            'role'     => \App\Http\Middleware\CheckRole::class,

            // Portal del postulante — JWT propio (lcobucci/jwt), SIN guards
            'postulante.jwt' => \App\Http\Middleware\PostulanteJwtMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
