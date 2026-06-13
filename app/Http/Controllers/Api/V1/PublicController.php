<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/PublicController.php
// (archivo NUEVO)
//
// Endpoints PÚBLICOS (sin autenticación) para:
//   - registroPostulante.blade.php
//   - modal "Olvidé mi contraseña" en login.blade.php
//
// GET  /api/v1/public/opciones-registro  → carreras + grupos con cupo/horario
// POST /api/v1/public/postulantes        → registro + asignación automática de grupo
// POST /api/v1/public/recuperar-clave    → verificar postulante por CI+correo
// ============================================================

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostulanteRequest;
use App\Models\Carrera;
use App\Models\Gestion;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Postulante;
use App\Services\PostulanteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function __construct(
        private readonly PostulanteService $service,
        private readonly \App\Services\GrupoAutomaticoService $grupoAutomatico
    ) {}

    /**
     * GET /api/v1/public/opciones-registro
     * Devuelve las carreras disponibles, los requisitos, y el
     * resumen de cupo por TURNO (Mañana / Tarde) — el postulante
     * elige un turno, no un grupo específico. El backend asigna
     * automáticamente el grupo con cupo correspondiente a ese turno.
     */
    public function opcionesRegistro(): JsonResponse
    {
        $carreras = Carrera::orderBy('txt_nombre')->get(['id_carrera', 'txt_nombre', 'int_cupo']);

        $requisitos = DB::table('tbl_requisito')
            ->orderBy('id_requisito')
            ->get(['id_requisito', 'txt_descripcion_requisito']);

        // Resumen de cupo agrupado por turno (id_turno: 1=Mañana, 2=Tarde)
        $turnos = DB::table('tbl_turno')->orderBy('id_turno')->get(['id_turno', 'txt_turno']);

        $resumenTurnos = $turnos->map(function ($turno) {
            // Grupos cuyo horario corresponde a este turno (cualquier
            // bloque del grupo basta, ya que un grupo tiene un solo turno)
            $idsGrupo = DB::table('tbl_grupo_horario as gh')
                ->join('tbl_horario as h', 'h.id_horario', '=', 'gh.id_horario')
                ->where('h.id_turno', $turno->id_turno)
                ->distinct()
                ->pluck('gh.id_grupo');

            $grupos = Grupo::whereIn('id_grupo', $idsGrupo)->get();

            $cuposDisponibles = $grupos->sum(fn($g) => max(0, $g->int_capacidad_maxma - $g->int_cantidad_estudiantes));
            $grupoConCupo = $grupos->first(fn($g) => $g->int_cantidad_estudiantes < $g->int_capacidad_maxma);

            // Horario representativo (mismo para todos los grupos del turno)
            $horarioMuestra = DB::table('tbl_horario')
                ->where('id_turno', $turno->id_turno)
                ->orderBy('tm_hora_inicio')
                ->get();

            $resumenHorario = $horarioMuestra->isNotEmpty()
                ? substr($horarioMuestra->first()->tm_hora_inicio, 0, 5) . ' - ' .
                  substr($horarioMuestra->last()->tm_hora_final, 0, 5)
                : null;

            return [
                'id_turno'          => $turno->id_turno,
                'txt_turno'         => $turno->txt_turno,
                'cupos_disponibles' => $cuposDisponibles,
                'horario_resumen'   => $resumenHorario,
                // Si no hay ningún grupo con cupo, el backend creará uno
                // nuevo automáticamente al registrar (ver registrarPostulante).
                'requiere_grupo_nuevo' => $grupoConCupo === null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'carreras' => $carreras,
                'requisitos' => $requisitos,
                'turnos_disponibles' => $resumenTurnos,
            ],
        ]);
    }

    /**
     * POST /api/v1/public/postulantes
     * Registro público de postulante. Crea:
     *   1. tbl_postulante
     *   2. tbl_inscripcion (PENDIENTE) con el grupo asignado
     *      automáticamente según el id_grupo elegido por horario
     *      (se valida que tenga cupo) — el sistema NO permite
     *      elegir un grupo lleno.
     *   3. tbl_inscripcion_carrera (1ra y 2da opción)
     *   4. Incrementa int_cantidad_estudiantes del grupo
     */
    public function registrarPostulante(StorePostulanteRequest $request): JsonResponse
    {
        $request->validate([
            'carreras'                  => ['required', 'array', 'min:2', 'max:2'],
            'carreras.*.id_carrera'     => ['required', 'integer', 'exists:tbl_carrera,id_carrera'],
            'carreras.*.int_prioridad'  => ['required', 'integer', 'in:1,2'],
            'id_turno'                  => ['required', 'integer', 'exists:tbl_turno,id_turno'],
            'requisitos'                => ['sometimes', 'array'],
            'requisitos.*'              => ['integer', 'exists:tbl_requisito,id_requisito'],
        ]);

        DB::beginTransaction();
        try {
            // Buscar, entre los grupos cuyo horario corresponde al turno
            // elegido, el primero que tenga cupo disponible.
            // lockForUpdate() evita condiciones de carrera con registros
            // simultáneos sobre el mismo grupo.
            $idsGrupoDelTurno = DB::table('tbl_grupo_horario as gh')
                ->join('tbl_horario as h', 'h.id_horario', '=', 'gh.id_horario')
                ->where('h.id_turno', $request->id_turno)
                ->distinct()
                ->pluck('gh.id_grupo');

            $grupo = Grupo::whereIn('id_grupo', $idsGrupoDelTurno)
                ->where('int_cantidad_estudiantes', '<', DB::raw('int_capacidad_maxma'))
                ->lockForUpdate()
                ->orderBy('id_grupo')
                ->first();

            if (! $grupo) {
                // No hay ningún grupo con cupo en este turno: crear uno
                // nuevo automáticamente (horario + aula + docentes).
                $grupo = $this->grupoAutomatico->crear((int) $request->id_turno);
            }

            // 1. Crear postulante
            $postulante = $this->service->crear($request->validated());

            // 2. Crear inscripción (PENDIENTE) con el grupo elegido
            $gestion = Gestion::orderByDesc('int_año')->first();
            $inscripcion = Inscripcion::create([
                'id_postulante'          => $postulante->id_postulante,
                'id_gestion'             => $gestion?->id_gestion ?? 1,
                'txt_estado_inscripcion' => 'PENDIENTE',
                'id_grupo'               => $grupo->id_grupo,
            ]);

            // 3. Registrar carreras elegidas
            foreach ($request->carreras as $c) {
                DB::table('tbl_inscripcion_carrera')->insert([
                    'id_inscripcion' => $inscripcion->id_inscripcion,
                    'id_carrera'     => $c['id_carrera'],
                    'int_prioridad'  => $c['int_prioridad'],
                ]);
            }

            // 4. Registrar requisitos presentados (si los envía)
            if ($request->filled('requisitos')) {
                foreach ($request->requisitos as $idRequisito) {
                    DB::table('tbl_requisito_postulante')->insert([
                        'id_postulante'    => $postulante->id_postulante,
                        'id_requisito'     => $idRequisito,
                        'fch_presentacion' => now(),
                    ]);
                }
            }

            // 5. Incrementar contador de estudiantes del grupo
            DB::table('tbl_grupo')
                ->where('id_grupo', $grupo->id_grupo)
                ->increment('int_cantidad_estudiantes');

            // 6. Crear registro de pago (PENDIENTE) — matrícula fija Bs 350
            // txt_metodo y txt_referencia son NOT NULL en la BD; se usan
            // placeholders hasta que el pago se procese (Stripe los
            // sobrescribe en confirmarPagoPorSesion()).
            $pago = Pago::create([
                'num_monto'      => 350.00,
                'txt_estado'     => 'PENDIENTE',
                'txt_metodo'     => 'PENDIENTE',
                'txt_referencia' => 'PENDIENTE-' . $inscripcion->id_inscripcion,
                'id_inscripcion' => $inscripcion->id_inscripcion,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registro exitoso. Tu usuario para ingresar al portal es tu correo y tu contraseña es tu Carnet de Identidad (CI).',
                'data'    => [
                    'id_postulante'  => $postulante->id_postulante,
                    'id_inscripcion' => $inscripcion->id_inscripcion,
                    'grupo'          => $grupo->txt_nombre,
                    'monto_matricula' => (float) $pago->num_monto,
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/public/recuperar-clave
     * Verifica que exista un postulante con ese CI + correo.
     * Como la "contraseña" del postulante ES su CI, simplemente
     * confirmamos la coincidencia y se la mostramos en pantalla
     * (no se envía correo — no hay SMTP configurado).
     */
    public function recuperarClave(Request $request): JsonResponse
    {
        $request->validate([
            'txt_ci'     => ['required', 'string'],
            'txt_correo' => ['required', 'email'],
        ]);

        $normalizar = fn(string $v): string => strtoupper(preg_replace('/\s+/', '', $v));

        $postulante = Postulante::where('txt_correo', $request->txt_correo)->first();

        if (! $postulante || $normalizar($postulante->txt_ci) !== $normalizar($request->txt_ci)) {
            return response()->json([
                'success' => false,
                'message' => 'No encontramos un postulante con ese CI y correo.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verificación correcta.',
            'data'     => [
                'txt_correo'    => $postulante->txt_correo,
                'txt_ci'        => $postulante->txt_ci, // = contraseña
            ],
        ]);
    }
}