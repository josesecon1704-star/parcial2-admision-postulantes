<?php

namespace App\Services;

// ============================================================
// DESTINO: app/Services/GrupoAutomaticoService.php
// (archivo NUEVO)
//
// Crea un grupo nuevo cuando, al registrarse un postulante, no
// existe ningún grupo con cupo en el turno elegido.
//
// El grupo nuevo se crea con:
//   - txt_nombre = "Grupo {N}" (N = MAX(id_grupo)+1)
//   - tbl_grupo_horario: 20 filas (5 días x 4 bloques del turno),
//     reusando la primera aula sin conflicto de horario, o
//     creando una aula nueva si ninguna califica.
//   - tbl_asignacion_docente: 4 filas (Computación, Matemáticas,
//     Inglés, Física, en ese orden — mismo orden usado por
//     PostulanteSelfController/DocenteController para emparejar
//     bloque-horario <-> materia). Para cada materia se busca un
//     docente con <4 grupos asignados; si ninguno califica, se usa
//     un docente placeholder "Docente Pendiente de Asignación".
// ============================================================

use App\Models\AsignacionDocente;
use App\Models\Aula;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Support\Facades\DB;

class GrupoAutomaticoService
{
    /** Orden fijo de materias para los 4 bloques diarios de un grupo. */
    private const ORDEN_MATERIAS = ['Computación', 'Matemáticas', 'Inglés', 'Física'];

    private const NOMBRE_DOCENTE_PENDIENTE = 'Docente Pendiente de Asignación';

    /**
     * Crea un grupo nuevo para el turno indicado, con horario,
     * aula y docentes asignados. Devuelve el Grupo creado.
     *
     * Debe llamarse dentro de una transacción ya abierta por el
     * caller (no abre/cierra su propia transacción).
     */
    public function crear(int $idTurno): Grupo
    {
        // 1. Crear el grupo primero (nombre temporal) y luego renombrar
        // usando el id_grupo REAL asignado por la secuencia de la BD.
        // No usamos MAX(id_grupo)+1 para el nombre porque puede
        // desincronizarse de la secuencia real si hubo inserts
        // fallidos previos (la secuencia de PostgreSQL avanza aunque
        // el INSERT se revierta).
        $grupo = Grupo::create([
            'txt_nombre'               => 'Grupo (nuevo)',
            'int_cantidad_estudiantes' => 0,
            'int_capacidad_maxma'      => 70,
        ]);

        $grupo->update(['txt_nombre' => "Grupo {$grupo->id_grupo}"]);

        // 2. Horarios del turno (20 filas: 5 días x 4 bloques)
        $horariosTurno = DB::table('tbl_horario')
            ->where('id_turno', $idTurno)
            ->orderBy('txt_dia_semana')
            ->orderBy('tm_hora_inicio')
            ->get();

        // 3. Aula: primera sin conflicto con estos 20 horarios,
        //    o crear una nueva si ninguna califica.
        $idAula = $this->elegirAula($horariosTurno->pluck('id_horario')->all());

        // 4. Insertar los 20 tbl_grupo_horario
        $filasHorario = $horariosTurno->map(fn($h) => [
            'id_grupo'   => $grupo->id_grupo,
            'id_horario' => $h->id_horario,
            'id_aula'    => $idAula,
        ])->all();

        DB::table('tbl_grupo_horario')->insert($filasHorario);

        // 5. Asignar docentes para las 4 materias (en orden fijo)
        $this->asignarDocentes($grupo->id_grupo);

        return $grupo;
    }

    /**
     * Devuelve un id_aula que no tenga conflicto con ninguno de los
     * id_horario dados (es decir, ningún otro grupo usa esa aula en
     * esos mismos bloques horarios). Si ninguna aula existente
     * califica, crea una aula nueva.
     */
    private function elegirAula(array $idsHorario): int
    {
        $aulas = Aula::orderBy('id_aula')->get();

        foreach ($aulas as $aula) {
            $conflicto = DB::table('tbl_grupo_horario')
                ->where('id_aula', $aula->id_aula)
                ->whereIn('id_horario', $idsHorario)
                ->exists();

            if (! $conflicto) {
                return $aula->id_aula;
            }
        }

        // Ninguna aula libre para este turno: crear una nueva.
        // Numeración simple basada en la cantidad de aulas existentes.
        $totalAulas = Aula::count();
        $nuevaAula = Aula::create([
            'int_piso'     => intdiv($totalAulas, 10) + 1,
            'txt_nro_aula' => 'Aula ' . (100 + $totalAulas + 1),
        ]);

        return $nuevaAula->id_aula;
    }

    /**
     * Crea las 4 filas de tbl_asignacion_docente para el grupo,
     * en el orden fijo de ORDEN_MATERIAS. Para cada materia busca
     * un docente con <4 grupos asignados (distintos); si ninguno
     * califica, usa el docente placeholder.
     */
    private function asignarDocentes(int $idGrupo): void
    {
        $idDocentePendiente = null; // se crea bajo demanda, una sola vez

        foreach (self::ORDEN_MATERIAS as $nombreMateria) {
            $materia = Materia::where('txt_nombre', $nombreMateria)->first();

            if (! $materia) {
                continue; // materia no configurada, no debería pasar
            }

            $idDocente = $this->buscarDocenteDisponible();

            if (! $idDocente) {
                $idDocentePendiente ??= $this->obtenerOcrearDocentePendiente();
                $idDocente = $idDocentePendiente;
            }

            AsignacionDocente::create([
                'id_grupo'   => $idGrupo,
                'id_materia' => $materia->id_materia,
                'id_docente' => $idDocente,
            ]);
        }
    }

    /**
     * Busca un docente (que no sea el placeholder) con menos de 4
     * grupos distintos asignados. Devuelve su id_docente o null si
     * ninguno califica.
     */
    private function buscarDocenteDisponible(): ?int
    {
        $docente = Docente::where('txt_nombre', '!=', self::NOMBRE_DOCENTE_PENDIENTE)
            ->get()
            ->first(function ($d) {
                $totalGrupos = AsignacionDocente::where('id_docente', $d->id_docente)
                    ->distinct('id_grupo')
                    ->count('id_grupo');

                return $totalGrupos < 4;
            });

        return $docente?->id_docente;
    }

    /**
     * Devuelve el id_docente del placeholder "Docente Pendiente de
     * Asignación", creándolo si todavía no existe. Este docente NO
     * cuenta para el límite de 4 grupos — se usa exclusivamente como
     * marcador para que el admin asigne un docente real después.
     */
    private function obtenerOcrearDocentePendiente(): int
    {
        $docente = Docente::where('txt_nombre', self::NOMBRE_DOCENTE_PENDIENTE)->first();

        if ($docente) {
            return $docente->id_docente;
        }

        $docente = Docente::create([
            'txt_ci'       => 'PENDIENTE',
            'txt_nombre'   => self::NOMBRE_DOCENTE_PENDIENTE,
            'txt_telefono' => null,
            'txt_correo'   => 'docente.pendiente@ficct.edu.bo',
        ]);

        return $docente->id_docente;
    }
}