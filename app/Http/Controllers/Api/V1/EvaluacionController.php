<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/EvaluacionController.php
//
// CU-21: POST /api/v1/evaluaciones          → crear examen
//        PUT  /api/v1/evaluaciones/{id}/detalles → guardar/editar notas
// CU-22: mismos endpoints (registrar = editar cuando ya existe)
//        GET  /api/v1/evaluaciones?id_postulante=X → listar exámenes
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\DetalleEvaluacion;
use App\Models\Evaluacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluacionController extends Controller
{
    // GET /api/v1/evaluaciones/todas
    // Devuelve TODAS las evaluaciones (de todos los postulantes) en
    // una sola consulta, agrupadas por id_postulante. Usado por
    // "Reportes Analíticos" (Por Materia / Promedios) para evitar
    // hacer un fetch individual por cada postulante (N+1).
    //
    // Respuesta:
    //   { success: true, data: { [id_postulante]: [ {int_nro_examen, detalles:[...]}, ... ] } }
    public function todas(): JsonResponse
    {
        $evaluaciones = Evaluacion::with(['detalles'])
            ->orderBy('id_postulante')
            ->orderBy('int_nro_examen')
            ->get();

        $agrupado = $evaluaciones
            ->groupBy('id_postulante')
            ->map(fn($grupo) => $grupo->map(fn($ev) => [
                'int_nro_examen' => $ev->int_nro_examen,
                'detalles'       => $ev->detalles->map(fn($d) => [
                    'id_materia' => $d->id_materia,
                    'num_nota'   => (float) $d->num_nota,
                ]),
            ])->values());

        return response()->json([
            'success' => true,
            'data'    => $agrupado,
        ]);
    }

    // GET /api/v1/evaluaciones?id_postulante=X
    // Devuelve los 3 exámenes del postulante con sus detalles por materia
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'id_postulante' => ['required', 'integer', 'exists:tbl_postulante,id_postulante'],
        ]);

        $evaluaciones = Evaluacion::with(['detalles.materia'])
            ->where('id_postulante', $request->id_postulante)
            ->orderBy('int_nro_examen')
            ->get()
            ->map(fn($ev) => $this->formatear($ev));

        return response()->json([
            'success' => true,
            'data'    => $evaluaciones,
        ]);
    }

    // POST /api/v1/evaluaciones
    // CU-21: Crear un nuevo examen para el postulante
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_postulante' => ['required', 'integer', 'exists:tbl_postulante,id_postulante'],
            'int_nro_examen'=> ['required', 'integer', 'in:1,2,3'],
            'fch_examen'    => ['required', 'date'],
        ]);

        // Verificar que no exista ya ese número de examen para ese postulante
        $existe = Evaluacion::where('id_postulante', $request->id_postulante)
            ->where('int_nro_examen', $request->int_nro_examen)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => "El Examen {$request->int_nro_examen} ya existe para este postulante.",
            ], 409);
        }

        $evaluacion = Evaluacion::create([
            'id_postulante'  => $request->id_postulante,
            'int_nro_examen' => $request->int_nro_examen,
            'fch_examen'     => $request->fch_examen,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Examen {$request->int_nro_examen} creado correctamente.",
            'data'    => $this->formatear($evaluacion),
        ], 201);
    }

    // PUT /api/v1/evaluaciones/{id}/detalles
    // CU-21/22: Guardar o actualizar las notas de las 4 materias
    public function guardarDetalles(Request $request, int $id): JsonResponse
    {
        $evaluacion = Evaluacion::find($id);
        if (! $evaluacion) {
            return response()->json(['success' => false, 'message' => 'Evaluación no encontrada.'], 404);
        }

        $request->validate([
            'detalles'              => ['required', 'array', 'min:1'],
            'detalles.*.id_materia' => ['required', 'integer', 'exists:tbl_materia,id_materia'],
            'detalles.*.num_nota'   => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->detalles as $det) {
                // updateOrCreate con PK compuesta
                DetalleEvaluacion::updateOrCreate(
                    [
                        'id_evaluacion' => $id,
                        'id_materia'    => $det['id_materia'],
                    ],
                    [
                        'num_nota' => $det['num_nota'],
                    ]
                );
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notas guardadas correctamente.',
                'data'    => $this->formatear($evaluacion->fresh(['detalles.materia'])),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar notas: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Helper: formatear evaluación para la respuesta ────────
    private function formatear(Evaluacion $ev): array
    {
        return [
            'id_evaluacion'  => $ev->id_evaluacion,
            'int_nro_examen' => $ev->int_nro_examen,
            'fch_examen'     => $ev->fch_examen?->format('Y-m-d'),
            'id_postulante'  => $ev->id_postulante,
            'detalles'       => $ev->relationLoaded('detalles')
                ? $ev->detalles->map(fn($d) => [
                    'id_materia'  => $d->id_materia,
                    'txt_materia' => $d->materia->txt_nombre ?? '—',
                    'num_nota'    => (float) $d->num_nota,
                ])
                : [],
        ];
    }
}