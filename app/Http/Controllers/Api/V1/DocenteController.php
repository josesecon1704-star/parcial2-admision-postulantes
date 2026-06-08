<?php

namespace App\Http\Controllers\Api\V1;

// ============================================================
// DESTINO: app/Http/Controllers/Api/V1/DocenteController.php
// CU-12: Registrar docente (con profesiones y formaciones)
// CU-13: Consultar carga horaria del docente autenticado
// ============================================================

use App\Http\Controllers\Controller;
use App\Models\Contratacion;
use App\Models\Docente;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    // ── CU-10 estilo: Listar docentes ────────────────────────
    public function index(): JsonResponse
    {
        $docentes = Docente::with([
            'contratacionActiva:id_contratacion,id_docente,txt_estado,num_salario,fch_contrato',
            'profesiones:id_profesion,txt_descripcion',
        ])
            ->orderBy('txt_nombre')
            ->get()
            ->map(fn($d) => $this->formatear($d));

        return response()->json(['success' => true, 'data' => $docentes]);
    }

    // ── CU-12: Registrar docente ─────────────────────────────
    // POST /api/v1/docentes
    // Body: datos del docente + array de profesiones + array de formaciones
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            // Datos básicos del docente
            'txt_ci'      => ['required', 'string', 'max:20', 'unique:tbl_docente,txt_ci'],
            'txt_nombre'  => ['required', 'string', 'max:100'],
            'txt_telefono' => ['nullable', 'string', 'max:20'],
            'txt_correo'  => ['required', 'email', 'max:100', 'unique:tbl_docente,txt_correo'],
            // Profesiones obligatorias (requisito del examen)
            'profesiones'                    => ['required', 'array', 'min:1'],
            'profesiones.*.id_profesion'     => ['required', 'integer', 'exists:tbl_profesion,id_profesion'],
            'profesiones.*.txt_titulo'       => ['required', 'string', 'max:100'],
            'profesiones.*.txt_universidad'  => ['required', 'string', 'max:150'],
            'profesiones.*.fch_emicion'      => ['required', 'date'],
            // Formaciones opcionales (Maestría, Diplomado)
            'formaciones'                    => ['sometimes', 'array'],
            'formaciones.*'                  => ['integer', 'exists:tbl_formacion_academica,id_formacion'],
            // Contratación inicial
            'contratacion'                   => ['required', 'array'],
            'contratacion.fch_contrato'      => ['required', 'date'],
            'contratacion.num_salario'       => ['required', 'numeric', 'min:0'],
            'contratacion.txt_observacion'   => ['nullable', 'string'],
        ], [
            'txt_ci.unique'     => 'Ya existe un docente registrado con ese CI.',
            'txt_correo.unique' => 'Ya existe un docente registrado con ese correo.',
            'profesiones.required' => 'Debe registrar al menos una profesión del docente.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear docente
            $docente = Docente::create($request->only(
                'txt_ci',
                'txt_nombre',
                'txt_telefono',
                'txt_correo'
            ));

            // --- NUEVO BLOQUE: Crear usuario automáticamente ---
            $rolDocente = Rol::where('txt_nombre', 'DOCENTE')->first();

            // Generar username (ej: juan.123)
            $username = strtolower(explode(' ', $request->txt_nombre)[0]) . '.' . $docente->id_docente;

            $usuario = Usuario::create([
                'id_rol'       => $rolDocente->id_rol,
                'txt_username' => $username,
                'txt_email'    => $request->txt_correo,
                'txt_password' => Hash::make('Docente' . $docente->id_docente . '!'),
                'bol_estado'   => true,
                'fch_creacion' => now(),
            ]);

            // Vincular usuario al docente (asumiendo que tu tabla docente tiene la columna id_usuario)
            $docente->update(['id_usuario' => $usuario->id_usuario]);
            // --------------------------------------------------

            // 2. Registrar profesiones
            $profesionesSync = [];
            foreach ($request->profesiones as $p) {
                $profesionesSync[$p['id_profesion']] = [
                    'txt_titulo'      => $p['txt_titulo'],
                    'txt_universidad' => $p['txt_universidad'],
                    'fch_emicion'     => $p['fch_emicion'],
                ];
            }
            $docente->profesiones()->attach($profesionesSync);

            // 3. Registrar formaciones
            if ($request->filled('formaciones')) {
                $docente->formaciones()->attach($request->formaciones);
            }

            // 4. Registrar contratación
            Contratacion::create([
                'id_docente'      => $docente->id_docente,
                'id_usuario'      => auth('api')->id(), // Quien registra la contratación
                'fch_contrato'    => $request->contratacion['fch_contrato'],
                'num_salario'     => $request->contratacion['num_salario'],
                'txt_estado'      => 'ACTIVO',
                'txt_observacion' => $request->contratacion['txt_observacion'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Docente registrado, con usuario de acceso y contrato creado.',
                'data'    => $this->formatear($docente->load('profesiones', 'formaciones', 'contratacionActiva')),
                'credenciales' => [ // Retornamos esto solo al crear
                    'username' => $usuario->txt_username,
                    'password' => 'Docente' . $docente->id_docente . '!'
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el docente: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Ver docente con todas sus relaciones ─────────────────
    public function show(int $id): JsonResponse
    {
        $docente = Docente::with([
            'profesiones',
            'formaciones',
            'contrataciones',
            'asignaciones.materia',
            'asignaciones.grupo',
        ])->find($id);

        if (! $docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatear($docente, conRelaciones: true),
        ]);
    }

    // ── CU-07 docente: Modificar datos ───────────────────────
    public function update(Request $request, int $id): JsonResponse
    {
        $docente = Docente::find($id);
        if (! $docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
        }

        $request->validate([
            'txt_ci'      => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('tbl_docente', 'txt_ci')->ignore($id, 'id_docente')
            ],
            'txt_nombre'  => ['sometimes', 'string', 'max:100'],
            'txt_telefono' => ['nullable', 'string', 'max:20'],
            'txt_correo'  => [
                'sometimes',
                'email',
                'max:100',
                Rule::unique('tbl_docente', 'txt_correo')->ignore($id, 'id_docente')
            ],
        ]);

        $docente->update($request->only('txt_ci', 'txt_nombre', 'txt_telefono', 'txt_correo'));

        return response()->json([
            'success' => true,
            'message' => 'Datos del docente actualizados.',
            'data'    => $docente->fresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $docente = Docente::find($id);
        if (! $docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
        }

        if ($docente->asignaciones()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el docente porque tiene grupos asignados.',
            ], 409);
        }

        $docente->delete();
        return response()->json(['success' => true, 'message' => 'Docente eliminado correctamente.']);
    }

    // ── CU-13: Consultar carga horaria del docente autenticado
    // GET /api/v1/docentes/mi-carga  (rol DOCENTE)
    public function miCarga(): JsonResponse
    {
        $usuario  = auth('api')->user();

        // Buscar el docente vinculado al usuario autenticado por correo
        $docente = Docente::where('txt_correo', $usuario->txt_email)->first();

        if (! $docente) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un docente vinculado a tu cuenta.',
            ], 404);
        }

        $asignaciones = $docente->asignaciones()
            ->with([
                'materia:id_materia,txt_nombre',
                'grupo:id_grupo,txt_nombre,int_cantidad_estudiantes,int_capacidad_maxma',
                'grupo.horarios.turno',
            ])
            ->get()
            ->map(fn($a) => [
                'id_asignacion'   => $a->id_asignacion,
                'materia'         => $a->materia->txt_nombre,
                'grupo'           => $a->grupo->txt_nombre,
                'estudiantes'     => $a->grupo->int_cantidad_estudiantes,
                'capacidad'       => $a->grupo->int_capacidad_maxma,
                'horarios'        => $a->grupo->horarios->map(fn($h) => [
                    'dia'         => $h->txt_dia_semana,
                    'inicio'      => $h->tm_hora_inicio,
                    'final'       => $h->tm_hora_final,
                    'turno'       => $h->turno->txt_turno,
                ]),
            ]);

        return response()->json([
            'success'         => true,
            'docente'         => $docente->txt_nombre,
            'total_grupos'    => $asignaciones->count(),
            'maximo_grupos'   => 4,
            'data'            => $asignaciones,
        ]);
    }

    // ── Helper privado: formatear docente ────────────────────
    private function formatear(Docente $d, bool $conRelaciones = false): array
    {
        $data = [
            'id_docente'   => $d->id_docente,
            'txt_ci'       => $d->txt_ci,
            'txt_nombre'   => $d->txt_nombre,
            'txt_telefono' => $d->txt_telefono,
            'txt_correo'   => $d->txt_correo,
        ];

        if ($conRelaciones) {
            $data['profesiones']   = $d->relationLoaded('profesiones') ? $d->profesiones : [];
            $data['formaciones']   = $d->relationLoaded('formaciones') ? $d->formaciones : [];
            $data['contrataciones'] = $d->relationLoaded('contrataciones') ? $d->contrataciones : [];
            $data['grupos_asignados'] = $d->relationLoaded('asignaciones')
                ? $d->asignaciones->count() : 0;
        }

        return $data;
    }

    // ── Buscar docente por CI (para el módulo Asignar Docente) ──
    // GET /api/v1/docentes/buscar-ci?ci=xxxx
    public function buscarPorCI(Request $request): JsonResponse
    {
        $request->validate(['ci' => ['required', 'string', 'min:2']]);

        $docente = Docente::with(['profesiones'])
            ->where('txt_ci', 'ilike', '%' . $request->ci . '%')
            ->first();

        if (! $docente) {
            return response()->json(['success' => false, 'message' => 'Docente no encontrado.'], 404);
        }

        // Contar grupos asignados actualmente
        $totalGrupos = $docente->asignaciones()->distinct('id_grupo')->count('id_grupo');

        return response()->json([
            'success' => true,
            'data'    => [
                'id_docente'             => $docente->id_docente,
                'txt_ci'                 => $docente->txt_ci,
                'txt_nombre'             => $docente->txt_nombre,
                'txt_correo'             => $docente->txt_correo,
                'txt_telefono'           => $docente->txt_telefono,
                'total_grupos_asignados' => $totalGrupos,
                'profesiones'            => $docente->profesiones->map(fn($p) => [
                    'id_profesion'    => $p->id_profesion,
                    'txt_descripcion' => $p->txt_descripcion,
                    'txt_titulo'      => $p->pivot->txt_titulo,
                ]),
            ],
        ]);
    }
}
