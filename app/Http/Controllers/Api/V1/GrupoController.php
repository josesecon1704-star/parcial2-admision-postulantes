<?php
namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/GrupoController.php
//
// CU-14: Calcular cantidad de grupos (CEIL(inscritos/80))
//         — el trigger T3 lo hace automáticamente en PostgreSQL
//         — este endpoint lo consulta/muestra
// CU-18: Mostrar estudiantes por grupo
// CU-19: Asignar docente a grupo
// CU-20: Asignar postulante a grupo (ajuste manual si necesario)
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\AsignacionDocente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\Inscripcion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    // ── CU-14: Ver grupos y calcular cuántos se necesitan ─────
    // GET /api/v1/grupos
    public function index(): JsonResponse
    {
        $grupos = Grupo::with([
            'inscripcion.postulante:id_postulante,txt_nombre',
            'horarios.turno',
            'asignaciones.docente:id_docente,txt_nombre',
            'asignaciones.materia:id_materia,txt_nombre',
        ])->orderBy('id_grupo')->get();

        $totalInscritos  = Inscripcion::where('txt_estado_inscripcion', 'PROCESADO')->count();
        $gruposNecesarios = $totalInscritos > 0 ? (int) ceil($totalInscritos / 80) : 0;

        return response()->json([
            'success'          => true,
            'resumen' => [
                'total_inscritos_procesados' => $totalInscritos,
                'formula'                    => 'CEIL(inscritos / 80)',
                'grupos_necesarios'          => $gruposNecesarios,
                'grupos_creados'             => $grupos->count(),
            ],
            'data' => $grupos->map(fn($g) => $this->formatearGrupo($g)),
        ]);
    }

    // ── CU-18: Ver estudiantes de un grupo ───────────────────
    // GET /api/v1/grupos/{id}/estudiantes
    public function estudiantes(int $id): JsonResponse
    {
        $grupo = Grupo::find($id);
        if (! $grupo) {
            return response()->json(['success' => false, 'message' => 'Grupo no encontrado.'], 404);
        }

        // Los estudiantes llegan a los grupos a través de sus inscripciones
        // tbl_grupo.id_inscripcion vincula el grupo con una inscripción específica
        // Para listar TODOS los estudiantes del grupo necesitamos las inscripciones procesadas
        $inscripciones = Inscripcion::with('postulante:id_postulante,txt_ci,txt_nombre,txt_correo,txt_telefono')
            ->where('txt_estado_inscripcion', 'PROCESADO')
            ->orderBy('fch_inscripcion')
            ->paginate(80);

        return response()->json([
            'success'  => true,
            'grupo'    => [
                'id_grupo'                => $grupo->id_grupo,
                'txt_nombre'              => $grupo->txt_nombre,
                'int_cantidad_estudiantes'=> $grupo->int_cantidad_estudiantes,
                'int_capacidad_maxma'     => $grupo->int_capacidad_maxma,
                'cupos_disponibles'       => $grupo->cupos_disponibles,
            ],
            'data' => $inscripciones,
        ]);
    }

    // ── CU-19: Asignar docente a grupo ───────────────────────
    // POST /api/v1/grupos/{id}/asignar-docente
    // El trigger T4 bloquea si el docente ya tiene 4 grupos
    public function asignarDocente(Request $request, int $id): JsonResponse
    {
        $grupo = Grupo::find($id);
        if (! $grupo) {
            return response()->json(['success' => false, 'message' => 'Grupo no encontrado.'], 404);
        }

        $request->validate([
            'id_docente' => ['required', 'integer', 'exists:tbl_docente,id_docente'],
            'id_materia' => ['required', 'integer', 'exists:tbl_materia,id_materia'],
        ]);

        // Verificar que no esté ya asignada esa materia en ese grupo
        $yaAsignado = AsignacionDocente::where('id_grupo', $id)
            ->where('id_materia', $request->id_materia)
            ->exists();

        if ($yaAsignado) {
            return response()->json([
                'success' => false,
                'message' => 'Esa materia ya tiene un docente asignado en este grupo.',
            ], 409);
        }

        try {
            // El trigger T4 se ejecuta aquí: bloquea si el docente ya tiene 4 grupos
            $asignacion = AsignacionDocente::create([
                'id_docente' => $request->id_docente,
                'id_materia' => $request->id_materia,
                'id_grupo'   => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Docente asignado correctamente al grupo.',
                'data'    => $asignacion->load('docente', 'materia', 'grupo'),
            ], 201);

        } catch (\Exception $e) {
            $mensaje = str_contains($e->getMessage(), 'RESTRICCIÓN')
                ? 'El docente ya tiene asignados 4 grupos (máximo permitido por la FICCT).'
                : 'Error al asignar docente: ' . $e->getMessage();

            return response()->json(['success' => false, 'message' => $mensaje], 422);
        }
    }

    // ── CU-20: Asignar horario y aula a grupo ────────────────
    // POST /api/v1/grupos/{id}/horarios
    public function asignarHorario(Request $request, int $id): JsonResponse
    {
        $grupo = Grupo::find($id);
        if (! $grupo) {
            return response()->json(['success' => false, 'message' => 'Grupo no encontrado.'], 404);
        }

        $request->validate([
            'id_horario' => ['required', 'integer', 'exists:tbl_horario,id_horario'],
            'id_aula'    => ['required', 'integer', 'exists:tbl_aula,id_aula'],
        ]);

        // Verificar que ese horario no esté ya asignado a ese grupo
        if ($grupo->horarios()->where('tbl_grupo_horario.id_horario', $request->id_horario)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Ese horario ya está asignado a este grupo.',
            ], 409);
        }

        // Insertar en tbl_grupo_horario (PK compuesta: id_grupo, id_horario)
        $grupo->horarios()->attach($request->id_horario, ['id_aula' => $request->id_aula]);

        return response()->json([
            'success' => true,
            'message' => 'Horario y aula asignados al grupo correctamente.',
            'data'    => $grupo->load('horarios.turno'),
        ], 201);
    }

    // ── Helper ────────────────────────────────────────────────
    private function formatearGrupo(Grupo $g): array
    {
        return [
            'id_grupo'                => $g->id_grupo,
            'txt_nombre'              => $g->txt_nombre,
            'int_cantidad_estudiantes'=> $g->int_cantidad_estudiantes,
            'int_capacidad_maxma'     => $g->int_capacidad_maxma,
            'cupos_disponibles'       => $g->cupos_disponibles,
            'docentes_asignados'      => $g->asignaciones->map(fn($a) => [
                'docente' => $a->docente->txt_nombre ?? '-',
                'materia' => $a->materia->txt_nombre ?? '-',
            ]),
            'horarios' => $g->horarios->map(fn($h) => [
                'dia'    => $h->txt_dia_semana,
                'inicio' => $h->tm_hora_inicio,
                'final'  => $h->tm_hora_final,
                'turno'  => $h->turno->txt_turno ?? '-',
            ]),
        ];
    }




    // En tu GrupoController o en un nuevo InfraestructuraController
    public function recursosDisponibles() {
        return response()->json([
            'materias' => Materia::all(),
            'aulas'    => Aula::where('int_capacidad', '>=', 80)->get(),
            'horarios' => Horario::with('turno')->get()
        ]);
    }
}
