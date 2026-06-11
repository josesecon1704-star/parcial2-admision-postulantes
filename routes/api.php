<?php

// ============================================================
// DESTINO: routes/api.php  — CICLO 1 + PORTAL DEL POSTULANTE
// ============================================================

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AulaController;
use App\Http\Controllers\Api\V1\CarreraController;
use App\Http\Controllers\Api\V1\DocenteController;
use App\Http\Controllers\Api\V1\EvaluacionController;
use App\Http\Controllers\Api\V1\GrupoController;
use App\Http\Controllers\Api\V1\HorarioController;
use App\Http\Controllers\Api\V1\InscripcionController;
use App\Http\Controllers\Api\V1\MateriaController;
use App\Http\Controllers\Api\V1\PostulanteController;
use App\Http\Controllers\Api\V1\PostulanteSelfController;
use App\Http\Controllers\Api\V1\ProfesionController;
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
// ── DIAGNÓSTICO TEMPORAL — BORRAR DESPUÉS ───────────────
    Route::get('debug/auth-config', [\App\Http\Controllers\Api\V1\DiagnosticoController::class, 'checkAuthConfig']);

    // ════════════════════════════════════════════════════════
    // RUTAS PROTEGIDAS
    // ════════════════════════════════════════════════════════
    Route::middleware('jwt.auth')->group(function () {
        Route::get('dashboard/metrics', [DashboardController::class, 'getMetrics']);
        Route::get('aulas',    [AulaController::class,    'index']);
        Route::get('materias', [MateriaController::class, 'index']);
        Route::get('carreras', [CarreraController::class, 'index']);
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
            Route::patch('inscripciones/{id}/estado', [InscripcionController::class, 'actualizarEstado']);

            // CU-12: Docentes
            Route::get('docentes/buscar-ci', [DocenteController::class, 'buscarPorCI']);
            Route::apiResource('docentes', DocenteController::class);

            // Profesiones (para select en formulario de docente)
            Route::get('profesiones', [ProfesionController::class, 'index']);

            // CU-21/22: Evaluaciones y notas
            Route::get('evaluaciones',                           [EvaluacionController::class, 'index']);
            Route::post('evaluaciones',                          [EvaluacionController::class, 'store']);
            Route::put('evaluaciones/{id}/detalles',             [EvaluacionController::class, 'guardarDetalles']);

            // CU-14/18: Grupos (consultar)
            Route::apiResource('grupos', GrupoController::class)->only(['index', 'show']);
            Route::get('grupos/{id}/estudiantes', [GrupoController::class, 'estudiantes']);
        });

        // ── Solo DOCENTE ────────────────────────────────────
        Route::middleware('role:DOCENTE')->group(function () {
            // CU-13: Carga horaria propia
            Route::get('docentes/mi-carga', [DocenteController::class, 'miCarga']);
        });

        // ════════════════════════════════════════════════════
        // ── Solo POSTULANTE ──────────────────────────────────
        // Portal de autoservicio (postulante.blade.php)
        // Todos estos endpoints resuelven el id_postulante desde
        // el claim del JWT (auth('api')->user()->id_postulante),
        // NUNCA desde un parámetro de la URL — así un postulante
        // jamás puede ver datos de otro.
        // ════════════════════════════════════════════════════
        Route::middleware('role:POSTULANTE')->prefix('postulante')->group(function () {
            // Perfil + inscripción + carreras + grupo (mismo formato
            // que PostulanteService::formatear(conRelaciones: true))
            Route::get('me', [PostulanteSelfController::class, 'me']);

            // Horario semanal del grupo asignado (días, horas, turno, aula)
            Route::get('horario', [PostulanteSelfController::class, 'horario']);

            // Exámenes (1/2/3) con notas por materia
            Route::get('evaluaciones', [PostulanteSelfController::class, 'evaluaciones']);
        });
    });
});

Route::fallback(fn() => response()->json([
    'success' => false,
    'message' => 'Endpoint no encontrado.',
], 404));