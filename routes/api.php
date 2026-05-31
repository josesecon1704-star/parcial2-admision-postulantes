<?php

// ============================================================
// DESTINO: routes/api.php  — CICLO 1 COMPLETO
// Todos los CU-01 al CU-20 registrados
// ============================================================

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AulaController;
use App\Http\Controllers\Api\V1\DocenteController;
use App\Http\Controllers\Api\V1\GrupoController;
use App\Http\Controllers\Api\V1\HorarioController;
use App\Http\Controllers\Api\V1\InscripcionController;
use App\Http\Controllers\Api\V1\MateriaController;
use App\Http\Controllers\Api\V1\PostulanteController;
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

    // ════════════════════════════════════════════════════════
    // RUTAS PROTEGIDAS
    // ════════════════════════════════════════════════════════
    Route::middleware('jwt.auth')->group(function () {
        Route::get('dashboard/metrics', [DashboardController::class, 'getMetrics']);
        Route::get('aulas', [AulaController::class, 'index']);
Route::get('materias', [MateriaController::class, 'index']);
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

        // ── ADMINISTRADOR o SECRETARIA ──────────────────────
        Route::middleware('role:ADMINISTRADOR,SECRETARIA')->group(function () {

            // CU-09: Buscar (va ANTES del apiResource)
            Route::get('postulantes/buscar', [PostulanteController::class, 'buscar']);

            // CU-06/07/08/10: CRUD Postulantes
            Route::apiResource('postulantes', PostulanteController::class);

            // CU-11: Inscripciones
            Route::apiResource('inscripciones', InscripcionController::class)
                ->only(['index', 'store', 'show', 'destroy']);

            // CU-12: Docentes
            Route::apiResource('docentes', DocenteController::class);

            // CU-14/18: Grupos (consultar)
            Route::apiResource('grupos', GrupoController::class)->only(['index', 'show']);
Route::get('grupos/{id}/estudiantes', [GrupoController::class, 'estudiantes']);
        });

        // ── Solo DOCENTE ────────────────────────────────────
        Route::middleware('role:DOCENTE')->group(function () {
            // CU-13: Carga horaria propia
            Route::get('docentes/mi-carga', [DocenteController::class, 'miCarga']);
        });

        
    });
});

Route::fallback(fn() => response()->json([
    'success' => false,
    'message' => 'Endpoint no encontrado.',
], 404));