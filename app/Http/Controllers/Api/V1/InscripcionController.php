<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/InscripcionController.php
// CU-11: Validar inscripción
//
// IMPORTANTE — Triggers activos en PostgreSQL:
//   T2 trg_validar_requisitos_admision:
//      BEFORE INSERT → bloquea si el postulante no entregó todos los requisitos
//   T3 trg_ejecutar_algoritmo_grupos:
//      AFTER UPDATE (estado=PROCESADO) → crea grupos con CEIL(n/80) automáticamente
//
// Laravel solo hace el INSERT/UPDATE — PostgreSQL hace el resto
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use App\Models\Gestion;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Postulante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscripcionController extends Controller
{
    // ── Listar inscripciones con filtros ─────────────────────
    public function index(Request $request): JsonResponse
    {
        $query = Inscripcion::with([
            'postulante:id_postulante,txt_ci,txt_nombre,txt_correo',
            'gestion:id_gestion,int_año,txt_periodo',
            'carreras:id_carrera,txt_nombre',
            'pago:id_pago,id_inscripcion,num_monto,txt_estado,txt_metodo',
        ]);

        if ($request->filled('estado')) {
            $query->where('txt_estado_inscripcion', $request->estado);
        }
        if ($request->filled('id_gestion')) {
            $query->where('id_gestion', $request->id_gestion);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderBy('fch_inscripcion', 'desc')->paginate(15),
        ]);
    }

    // ── CU-11: Registrar inscripción + pago + carreras ───────
    // POST /api/v1/inscripciones
    // El trigger T2 bloquea automáticamente si faltan requisitos
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_postulante'   => ['required', 'integer', 'exists:tbl_postulante,id_postulante'],
            'id_gestion'      => ['required', 'integer', 'exists:tbl_gestion,id_gestion'],
            // Carreras: 1ra y 2da opción (prioridades 1 y 2)
            'carreras'                   => ['required', 'array', 'min:1', 'max:2'],
            'carreras.*.id_carrera'      => ['required', 'integer', 'exists:tbl_carrera,id_carrera'],
            'carreras.*.int_prioridad'   => ['required', 'integer', 'in:1,2'],
            // Pago
            'pago'                       => ['required', 'array'],
            'pago.num_monto'             => ['required', 'numeric', 'min:0'],
            'pago.txt_metodo'            => ['required', 'string', 'max:100'],
            'pago.txt_referencia'        => ['nullable', 'string', 'max:100'],
        ], [
            'carreras.*.id_carrera.exists'   => 'Una de las carreras seleccionadas no existe.',
            'carreras.*.int_prioridad.in'    => 'La prioridad solo puede ser 1 (primera) o 2 (segunda opción).',
        ]);

        // Verificar que no exista inscripción previa en esta gestión
        $yaInscrito = Inscripcion::where('id_postulante', $request->id_postulante)
            ->where('id_gestion', $request->id_gestion)
            ->exists();

        if ($yaInscrito) {
            return response()->json([
                'success' => false,
                'message' => 'El postulante ya tiene una inscripción registrada en esta gestión.',
            ], 409);
        }

        DB::beginTransaction();
        try {
            // 1. Crear inscripción
            // El trigger T2 se ejecuta aquí: si faltan requisitos, PostgreSQL lanza excepción
            $inscripcion = Inscripcion::create([
                'id_postulante'          => $request->id_postulante,
                'id_gestion'             => $request->id_gestion,
                'txt_estado_inscripcion' => 'PENDIENTE',
                'fch_inscripcion'        => now(),
            ]);

            // 2. Registrar carreras elegidas con prioridad
            $carrerasSync = [];
            foreach ($request->carreras as $c) {
                $carrerasSync[$c['id_carrera']] = ['int_prioridad' => $c['int_prioridad']];
            }
            $inscripcion->carreras()->attach($carrerasSync);

            // 3. Registrar pago
            Pago::create([
                'id_inscripcion'  => $inscripcion->id_inscripcion,
                'num_monto'       => $request->pago['num_monto'],
                'txt_estado'      => 'CONFIRMADO',
                'txt_metodo'      => $request->pago['txt_metodo'],
                'txt_referencia'  => $request->pago['txt_referencia'] ?? null,
                'fch_pago'        => now(),
            ]);

            // 4. Cambiar estado a PROCESADO
            // El trigger T3 se ejecuta aquí: crea/ajusta grupos con CEIL(n/80)
            $inscripcion->update(['txt_estado_inscripcion' => 'PROCESADO']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inscripción registrada y procesada. Grupos actualizados automáticamente.',
                'data'    => $inscripcion->load('postulante', 'gestion', 'carreras', 'pago'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Si el error viene del trigger T2 (requisitos incompletos)
            $mensaje = str_contains($e->getMessage(), 'BLOQUEADO')
                ? 'El postulante no puede inscribirse porque adeuda requisitos físicos en ventanilla.'
                : 'Error al procesar la inscripción: ' . $e->getMessage();

            return response()->json([
                'success' => false,
                'message' => $mensaje,
            ], 422);
        }
    }

    // ── Ver inscripción ───────────────────────────────────────
    public function show(int $id): JsonResponse
    {
        $inscripcion = Inscripcion::with([
            'postulante',
            'gestion',
            'carreras',
            'pago',
        ])->find($id);

        if (! $inscripcion) {
            return response()->json(['success' => false, 'message' => 'Inscripción no encontrada.'], 404);
        }

        return response()->json(['success' => true, 'data' => $inscripcion]);
    }

    // ── Anular inscripción ────────────────────────────────────
    public function destroy(int $id): JsonResponse
    {
        $inscripcion = Inscripcion::find($id);
        if (! $inscripcion) {
            return response()->json(['success' => false, 'message' => 'Inscripción no encontrada.'], 404);
        }

        if ($inscripcion->txt_estado_inscripcion === 'PROCESADO') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una inscripción ya procesada.',
            ], 409);
        }

        $inscripcion->delete();
        return response()->json(['success' => true, 'message' => 'Inscripción anulada.']);
    }

    public function actualizarEstado(Request $request, int $id): JsonResponse
    {
        $inscripcion = \App\Models\Inscripcion::find($id);

        if (!$inscripcion) {
            return response()->json([
                'success' => false,
                'message' => 'Inscripción no encontrada.',
            ], 404);
        }

        $request->validate([
            'txt_estado_inscripcion' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::in(['PENDIENTE', 'PROCESADO', 'ANULADO']),
            ],
        ]);

        $estadoAnterior = $inscripcion->txt_estado_inscripcion;
        $estadoNuevo    = $request->txt_estado_inscripcion;

        $inscripcion->update([
            'txt_estado_inscripcion' => $estadoNuevo,
        ]);

        // Si cambia a PROCESADO y tiene grupo asignado → incrementar contador
        if ($estadoAnterior !== 'PROCESADO' && $estadoNuevo === 'PROCESADO' && $inscripcion->id_grupo) {
            \App\Models\Grupo::where('id_grupo', $inscripcion->id_grupo)
                ->increment('int_cantidad_estudiantes');
        }

        // Si sale de PROCESADO → decrementar contador
        if ($estadoAnterior === 'PROCESADO' && $estadoNuevo !== 'PROCESADO' && $inscripcion->id_grupo) {
            \App\Models\Grupo::where('id_grupo', $inscripcion->id_grupo)
                ->decrement('int_cantidad_estudiantes');
        }

        return response()->json([
            'success' => true,
            'message' => "Estado actualizado: {$estadoAnterior} → {$estadoNuevo}.",
            'data'    => [
                'id_inscripcion'         => $inscripcion->id_inscripcion,
                'txt_estado_inscripcion' => $inscripcion->txt_estado_inscripcion,
            ],
        ]);
    }
}
