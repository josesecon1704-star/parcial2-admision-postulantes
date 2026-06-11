<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/PostulanteSelfController.php
// (archivo NUEVO)
//
// Portal de autoservicio del postulante (postulante.blade.php).
// Protegido por middleware('role:POSTULANTE').
//
// El id_postulante SIEMPRE se obtiene del token JWT
// (auth('api')->user()), nunca de la URL ni del body —
// así un postulante no puede ver datos de otro.
//
// GET /api/v1/postulante/me            → perfil + inscripción + grupo
// GET /api/v1/postulante/horario       → horario semanal del grupo
// GET /api/v1/postulante/evaluaciones  → exámenes y notas
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Postulante;
use App\Services\PostulanteService;
use Illuminate\Http\JsonResponse;

class PostulanteSelfController extends Controller
{
    public function __construct(
        private readonly PostulanteService $service
    ) {}

    /**
     * GET /api/v1/postulante/me
     * Mismo formato que PostulanteController::show(), pero
     * solo para el postulante autenticado.
     */
    public function me(): JsonResponse
    {
        $postulante = Postulante::find(auth('api_postulante')->id());

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->service->formatear($postulante, conRelaciones: true),
        ], 200);
    }

    /**
     * GET /api/v1/postulante/horario
     * Devuelve el grupo del postulante (con cantidad/capacidad)
     * y su horario semanal (día, horas, turno, aula).
     *
     * Si el postulante aún no tiene grupo asignado (id_grupo null
     * en su última inscripción), 'grupo' viene null.
     */
    public function horario(): JsonResponse
    {
        $postulante = Postulante::find(auth('api_postulante')->id());

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        $inscripcion = $postulante->inscripciones()
            ->with(['grupo.horarios.turno', 'grupo.horarios'])
            ->latest('fch_inscripcion')
            ->first();

        $grupo = $inscripcion?->grupo;

        if (! $grupo) {
            return response()->json([
                'success' => true,
                'data'    => [
                    'grupo'    => null,
                    'horarios' => [],
                ],
            ], 200);
        }

        // Cargar aulas asociadas a cada horario (pivote tbl_grupo_horario.id_aula)
        $horarios = $grupo->horarios->map(function ($h) {
            $aula = $h->pivot->id_aula
                ? \App\Models\Aula::find($h->pivot->id_aula)
                : null;

            return [
                'id_horario'     => $h->id_horario,
                'txt_dia_semana' => $h->txt_dia_semana,
                'tm_hora_inicio' => $h->tm_hora_inicio,
                'tm_hora_final'  => $h->tm_hora_final,
                'turno'          => $h->turno ? [
                    'id_turno'   => $h->turno->id_turno,
                    'txt_nombre' => $h->turno->txt_nombre,
                ] : null,
                'id_aula'        => $h->pivot->id_aula,
                'aula'           => $aula ? [
                    'id_aula'      => $aula->id_aula,
                    'int_piso'     => $aula->int_piso,
                    'txt_nro_aula' => $aula->txt_nro_aula,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'grupo' => [
                    'id_grupo'                 => $grupo->id_grupo,
                    'txt_nombre'               => $grupo->txt_nombre,
                    'int_cantidad_estudiantes' => $grupo->int_cantidad_estudiantes,
                    'int_capacidad_maxma'      => $grupo->int_capacidad_maxma,
                ],
                'horarios' => $horarios,
            ],
        ], 200);
    }

    /**
     * GET /api/v1/postulante/evaluaciones
     * Mismo formato que EvaluacionController::index(), pero
     * solo para el postulante autenticado (sin parámetro id_postulante).
     */
    public function evaluaciones(): JsonResponse
    {
        $postulante = Postulante::find(auth('api_postulante')->id());

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        $evaluaciones = Evaluacion::with(['detalles.materia'])
            ->where('id_postulante', $postulante->id_postulante)
            ->orderBy('int_nro_examen')
            ->get()
            ->map(fn($ev) => [
                'id_evaluacion'  => $ev->id_evaluacion,
                'int_nro_examen' => $ev->int_nro_examen,
                'fch_examen'     => $ev->fch_examen?->format('Y-m-d'),
                'detalles'       => $ev->detalles->map(fn($d) => [
                    'id_materia'  => $d->id_materia,
                    'txt_materia' => $d->materia->txt_nombre ?? '—',
                    'num_nota'    => (float) $d->num_nota,
                ]),
            ]);

        return response()->json([
            'success' => true,
            'data'    => $evaluaciones,
        ], 200);
    }
}