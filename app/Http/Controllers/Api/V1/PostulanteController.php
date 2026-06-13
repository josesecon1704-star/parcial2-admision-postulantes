<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/PostulanteController.php
//
// CU-06  POST   /api/v1/postulantes          → store   (registrar)
// CU-07  PUT    /api/v1/postulantes/{id}      → update  (modificar)
// CU-08  DELETE /api/v1/postulantes/{id}      → destroy (eliminar)
// CU-09  GET    /api/v1/postulantes/buscar    → buscar  (búsqueda)
// CU-10  GET    /api/v1/postulantes           → index   (listar)
//         GET    /api/v1/postulantes/{id}      → show    (ver uno)
// ============================================================

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostulanteRequest;
use App\Http\Requests\UpdatePostulanteRequest;
use App\Models\Postulante;
use App\Services\AdmisionService;
use App\Services\PostulanteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostulanteController extends Controller
{
    public function __construct(
        private readonly PostulanteService $service,
        private readonly AdmisionService $admision
    ) {}

    // ──────────────────────────────────────────────────────
    // CU-10: Listar postulantes
    // GET /api/v1/postulantes
    // Query: ?buscar=texto  ?ciudad=LaPaz  ?sexo=M  ?per_page=15
    // ──────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $postulantes = $this->service->listar(
            filtros: $request->only(['buscar', 'ciudad', 'sexo']),
            perPage: (int) $request->get('per_page', 15),
            conRelaciones: true,
        );

        // Resultado de admisión (nota final, ranking, carrera admitida)
        // calculado UNA SOLA VEZ para todos los postulantes, no por fila.
        $resultadosAdmision = $this->admision->calcularResultados();

        return response()->json([
            'success' => true,
            'data'    => $postulantes->through(
                fn($p) => $this->service->formatear(
                    $p,
                    conRelaciones: true,
                    resultadoAdmision: $resultadosAdmision->get($p->id_postulante)
                )
            ),
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // CU-09: Buscar postulante
    // GET /api/v1/postulantes/buscar?q=texto
    // Busca por CI, nombre o correo — respuesta rápida sin paginar
    // IMPORTANTE: esta ruta debe ir ANTES de show() en api.php
    // ──────────────────────────────────────────────────────
    public function buscar(Request $request): JsonResponse
    {
        $request->validate([
            'q' => ['required', 'string', 'min:2'],
        ]);

        $termino = $request->q;

        $postulantes = Postulante::with([
                'inscripciones' => fn($q) => $q->latest('fch_inscripcion')
                    ->with(['carreras', 'grupo', 'gestion', 'pago']),
            ])
            ->where('txt_ci',     'ilike', "%{$termino}%")
            ->orWhere('txt_nombre', 'ilike', "%{$termino}%")
            ->orWhere('txt_correo', 'ilike', "%{$termino}%")
            ->orderBy('txt_nombre')
            ->limit(20)
            ->get();

        return response()->json([
            'success'   => true,
            'total'     => $postulantes->count(),
            'data'      => $postulantes->map(
                fn($p) => $this->service->formatear($p)
            ),
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // Ver detalle de un postulante con sus relaciones
    // GET /api/v1/postulantes/{id}
    // ──────────────────────────────────────────────────────
    public function show(int $id): JsonResponse
    {
        $postulante = Postulante::with([
                'inscripciones' => fn($q) => $q->latest('fch_inscripcion')
                    ->with(['carreras', 'grupo', 'gestion', 'pago']),
            ])
            ->withCount('requisitos')
            ->find($id);

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        $resultadoAdmision = $this->admision->resultadoDe($id);

        return response()->json([
            'success' => true,
            'data'    => $this->service->formatear(
                $postulante,
                conRelaciones: true,
                resultadoAdmision: $resultadoAdmision
            ),
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // CU-06: Registrar postulante
    // POST /api/v1/postulantes
    // El trigger trg_auditar_postulantes registra el INSERT
    // automáticamente en tbl_auditoria (no hay que hacerlo manual)
    // ──────────────────────────────────────────────────────
    public function store(StorePostulanteRequest $request): JsonResponse
    {
        // Validación adicional para carreras y grupo
        $request->validate([
            'carreras'                 => ['sometimes', 'array', 'min:1', 'max:2'],
            'carreras.*.id_carrera'    => ['required_with:carreras', 'integer', 'exists:tbl_carrera,id_carrera'],
            'carreras.*.int_prioridad' => ['required_with:carreras', 'integer', 'in:1,2'],
            'id_grupo'                 => ['nullable', 'integer', 'exists:tbl_grupo,id_grupo'],
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear postulante en tbl_postulante
            $postulante = $this->service->crear($request->validated());

            // 2. Crear inscripción automáticamente con el grupo elegido
            $gestion = \App\Models\Gestion::orderByDesc('int_año')->first();
            $inscripcion = \App\Models\Inscripcion::create([
                'id_postulante'          => $postulante->id_postulante,
                'id_gestion'             => $gestion?->id_gestion ?? 1,
                'txt_estado_inscripcion' => 'PENDIENTE',
                'id_grupo'               => $request->id_grupo ?? null,
            ]);

            // 3. Registrar carreras elegidas en tbl_inscripcion_carrera
            if ($request->filled('carreras')) {
                foreach ($request->carreras as $c) {
                    DB::table('tbl_inscripcion_carrera')->insert([
                        'id_inscripcion' => $inscripcion->id_inscripcion,
                        'id_carrera'     => $c['id_carrera'],
                        'int_prioridad'  => $c['int_prioridad'],
                    ]);
                }
            }

            // 4. Actualizar contador de estudiantes del grupo
            if ($request->id_grupo) {
                DB::table('tbl_grupo')
                    ->where('id_grupo', $request->id_grupo)
                    ->increment('int_cantidad_estudiantes');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Postulante registrado correctamente.',
                'data'    => $this->service->formatear($postulante->fresh()),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $e->getMessage(),
            ], 500);
        }
    }
    // ──────────────────────────────────────────────────────
    // CU-07: Modificar datos del postulante
    // PUT /api/v1/postulantes/{id}
    // El trigger trg_auditar_postulantes registra el UPDATE
    // automáticamente en tbl_auditoria
    // ──────────────────────────────────────────────────────
    public function update(UpdatePostulanteRequest $request, int $id): JsonResponse
    {
        $postulante = Postulante::find($id);

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        $actualizado = $this->service->actualizar(
            $postulante,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Datos del postulante actualizados correctamente.',
            'data'    => $this->service->formatear($actualizado),
        ], 200);
    }

    // ──────────────────────────────────────────────────────
    // CU-08: Eliminar registro de postulante
    // DELETE /api/v1/postulantes/{id}
    // Eliminación FÍSICA — el trigger registra el DELETE en auditoria
    // Se bloquea si tiene inscripciones activas
    // ──────────────────────────────────────────────────────
    public function destroy(int $id): JsonResponse
    {
        $postulante = Postulante::find($id);

        if (! $postulante) {
            return response()->json([
                'success' => false,
                'message' => 'Postulante no encontrado.',
            ], 404);
        }

        try {
            $this->service->eliminar($postulante);

            return response()->json([
                'success' => true,
                'message' => "Postulante '{$postulante->txt_nombre}' eliminado correctamente.",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 409); // 409 Conflict — no se puede eliminar por dependencias
        }
    }
}