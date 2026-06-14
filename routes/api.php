<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AulaController;
use App\Http\Controllers\Api\V1\CarreraController;
use App\Http\Controllers\Api\V1\DocenteController;
use App\Http\Controllers\Api\V1\EvaluacionController;
use App\Http\Controllers\Api\V1\AdmisionController;
use App\Http\Controllers\Api\V1\GrupoController;
use App\Http\Controllers\Api\V1\HorarioController;
use App\Http\Controllers\Api\V1\InscripcionController;
use App\Http\Controllers\Api\V1\MateriaController;
use App\Http\Controllers\Api\V1\PostulanteController;
use App\Http\Controllers\Api\V1\PostulanteSelfController;
use App\Http\Controllers\Api\V1\ProfesionController;
use App\Http\Controllers\Api\V1\PublicController;
use App\Http\Controllers\Api\V1\PagoController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use App\Http\Controllers\Api\V1\RolController;
use App\Http\Controllers\Api\V1\TurnoController;
use App\Http\Controllers\Api\V1\UsuarioController;
use App\Http\Controllers\Api\V1\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // ════════════════════════════════════════════════════════
    // AUTENTICACIÓN — públicas
    // ════════════════════════════════════════════════════════
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login',   [AuthController::class, 'login'])->name('login');
        Route::middleware('jwt.auth')->group(function () {
            Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('/me',       [AuthController::class, 'me'])->name('me');
        });
    });

    // (rutas de diagnóstico eliminadas tras resolver el login del postulante)

    // ════════════════════════════════════════════════════════
    // ── PÚBLICAS — Registro de postulante / recuperación ────
    // Sin autenticación. Usadas por registroPostulante.blade.php
    // y el modal "Olvidé mi contraseña" en login.blade.php.
    // ════════════════════════════════════════════════════════
    Route::prefix('public')->group(function () {
        Route::get('opciones-registro', [PublicController::class, 'opcionesRegistro']);
        Route::post('postulantes', [PublicController::class, 'registrarPostulante']);
        Route::post('recuperar-clave', [PublicController::class, 'recuperarClave']);

        // Pagos (Stripe Checkout) — matrícula de admisión
        Route::post('pagos/{idInscripcion}/crear-sesion', [PagoController::class, 'crearSesionPublica']);
        Route::get('pagos/estado/{sessionId}', [PagoController::class, 'estado']);
    });

    // Webhook de Stripe — sin autenticación, verificado por firma
    Route::post('stripe/webhook', [StripeWebhookController::class, 'handle']);

    // ════════════════════════════════════════════════════════
    // ── PORTAL DEL POSTULANTE ────────────────────────────────
    // JWT propio (lcobucci/jwt), validado manualmente — NO usa
    // guards de Laravel ni tymon/jwt-auth. Ver PostulanteJwtMiddleware.
    // ════════════════════════════════════════════════════════
    Route::middleware('postulante.jwt')->prefix('postulante')->group(function () {
        // Perfil + inscripción + carreras + grupo (mismo formato
        // que PostulanteService::formatear(conRelaciones: true))
        Route::get('me', [PostulanteSelfController::class, 'me']);

        // Horario semanal del grupo asignado (días, horas, turno, aula)
        Route::get('horario', [PostulanteSelfController::class, 'horario']);

        // Exámenes (1/2/3) con notas por materia
        Route::get('evaluaciones', [PostulanteSelfController::class, 'evaluaciones']);

        // Pagar matrícula desde el portal (si sigue PENDIENTE)
        Route::post('pagos/crear-sesion', [PagoController::class, 'crearSesionPostulante']);
    });

    // ════════════════════════════════════════════════════════
    // RUTAS PROTEGIDAS — personal administrativo
    // ════════════════════════════════════════════════════════
    Route::middleware('jwt.auth')->group(function () {
        Route::get('dashboard/metrics', [DashboardController::class, 'getMetrics']);
        Route::get('aulas',      [AulaController::class,    'index']);
        Route::get('materias',   [MateriaController::class, 'index']);
        Route::get('carreras',   [CarreraController::class, 'index']);
        Route::get('requisitos', fn() => response()->json([
            'success' => true,
            'data'    => \Illuminate\Support\Facades\DB::table('tbl_requisito')
                ->orderBy('id_requisito')
                ->get(['id_requisito', 'txt_descripcion_requisito']),
        ]));
        // ── Solo ADMINISTRADOR ──────────────────────────────
        Route::middleware('role:ADMINISTRADOR')->group(function () {

            // CU-04: Usuarios
            Route::apiResource('usuarios', UsuarioController::class);
            Route::patch('usuarios/{usuario}/estado', [UsuarioController::class, 'toggleEstado']);

            // CU-05: Roles
            Route::patch('usuarios/{usuario}/rol', [RolController::class, 'asignarRol']);
            Route::get('roles',      [RolController::class, 'index']);
            Route::get('roles/{id}', [RolController::class, 'show']);

            // CU-15: Materias (exactamente 4)
            Route::apiResource('materias', MateriaController::class);

            // CU-16: Aulas
            Route::apiResource('aulas', AulaController::class);

            // CU-17: Horarios y Turnos
            Route::apiResource('horarios', HorarioController::class);
            Route::get('turnos',     [TurnoController::class, 'index']);
            Route::post('turnos',    [TurnoController::class, 'store']);
            Route::delete('turnos/{id}', [TurnoController::class, 'destroy']);

            // CU-19/20: Asignación de docentes y horarios a grupos
            Route::post('grupos/{id}/asignar-docente', [GrupoController::class, 'asignarDocente']);
            Route::post('grupos/{id}/horarios',        [GrupoController::class, 'asignarHorario']);
        });

        
        Route::middleware('role:DOCENTE')->group(function () {
            // CU-13: Carga horaria propia
            Route::get('docentes/mi-carga', [DocenteController::class, 'miCarga']);
            // Perfil propio
            Route::get('docentes/me', [DocenteController::class, 'me']);
        });

        // ── ADMINISTRADOR o SECRETARIA ──────────────────────
        Route::middleware('role:ADMINISTRADOR,SECRETARIA')->group(function () {

            // CU-09: Buscar (va ANTES del apiResource)
            Route::get('postulantes/buscar', [PostulanteController::class, 'buscar']);

            // CU-06/07/08/10: CRUD Postulantes
            Route::apiResource('postulantes', PostulanteController::class);

            // CU-11: Inscripciones
            Route::apiResource('inscripciones', InscripcionController::class)
                ->only(['index', 'store', 'show', 'destroy']);
            Route::patch('inscripciones/{id}/estado', [InscripcionController::class, 'actualizarEstado']);

            // CU-12: Docentes
            Route::get('docentes/buscar-ci', [DocenteController::class, 'buscarPorCI']);
            Route::apiResource('docentes', DocenteController::class);

            // Profesiones (para select en formulario de docente)
            Route::get('profesiones', [ProfesionController::class, 'index']);

            // CU-21/22: Evaluaciones y notas
            // evaluaciones/todas DEBE ir antes de evaluaciones/{id}/...
            Route::get('evaluaciones/todas',                    [EvaluacionController::class, 'todas']);

            // Admisión: resumen de cupos por carrera (Reportes Analíticos)
            Route::get('admision/resumen-cupos', [AdmisionController::class, 'resumenCupos']);
            Route::get('evaluaciones',                           [EvaluacionController::class, 'index']);
            Route::post('evaluaciones',                          [EvaluacionController::class, 'store']);
            Route::put('evaluaciones/{id}/detalles',             [EvaluacionController::class, 'guardarDetalles']);

            // CU-14/18: Grupos (consultar)
            Route::apiResource('grupos', GrupoController::class)->only(['index', 'show']);
            Route::get('grupos/{id}/estudiantes', [GrupoController::class, 'estudiantes']);
        });
    });
});

Route::fallback(fn() => response()->json([
    'success' => false,
    'message' => 'Endpoint no encontrado.',
], 404));