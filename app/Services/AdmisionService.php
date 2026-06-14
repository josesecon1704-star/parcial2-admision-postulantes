<?php

namespace App\Services;

// ============================================================
// Calcula EN TIEMPO REAL (sin persistir en BD) el resultado de
// admisión de cada postulante:
//
//   1. APROBADO: tiene 3 evaluaciones x 4 materias = 12 notas,
//      y TODAS las notas individuales (num_nota) >= 60.
//      Si le faltan evaluaciones/detalles o alguna nota < 60
//      -> NO_APROBADO (no entra al ranking).
//
//   2. nota_final = promedio de los 4 num_promedio_materia
//      (cada uno ya es el promedio de sus 3 exámenes) = promedio
//      general de las 12 notas.
//
//   3. Se ordenan los APROBADOS por nota_final desc.
//
//   4. TOP 300 (CUPO_ADMISION): se recorren en orden de ranking,
//      intentando admitir en carrera_1 si tbl_carrera.int_cupo
//      tiene espacio (contando cupos ya usados dinámicamente);
//      si carrera_1 está llena, intenta carrera_2; si ambas
//      llenas -> NO_ADMITIDO.
//
//   5. Aprobados fuera del top 300 (posición 301+): van directo
//      a carrera_2, también con verificación de cupo. Si
//      carrera_2 está llena -> NO_ADMITIDO.
//
// Resultados posibles (txt_resultado):
//   'ADMITIDO_CARRERA_1' | 'ADMITIDO_CARRERA_2' |
//   'NO_ADMITIDO' | 'NO_APROBADO'
// ============================================================

use App\Models\Postulante;
use Illuminate\Support\Collection;

class AdmisionService
{
    /** Cupo total de admisión a la universidad (top N por nota). */
    private const CUPO_ADMISION = 300;

    /**
     * Calcula el resultado de admisión para TODOS los postulantes
     * que tienen una inscripción con carreras elegidas.
     *
     * Devuelve una Collection indexada por id_postulante, cada item:
     *   [
     *     'id_postulante'    => int,
     *     'nota_final'       => float|null,
     *     'aprobado'         => bool,
     *     'ranking'          => int|null,   // posición 1..N entre aprobados
     *     'txt_resultado'    => string,     // ADMITIDO_CARRERA_1 | ADMITIDO_CARRERA_2 | NO_ADMITIDO | NO_APROBADO
     *     'id_carrera_admitida' => int|null,
     *     'txt_carrera_admitida'=> string|null,
     *   ]
     */
    /** Cupos totales por id_carrera, calculados en la última llamada a calcularResultados(). */
    private ?Collection $ultimosCupos = null;

    /** Cupos usados por id_carrera, calculados en la última llamada a calcularResultados(). */
    private ?array $ultimosCuposUsados = null;

    public function calcularResultados(): Collection
    {
        // 1. Cupos por carrera: id_carrera => int_cupo
        $cupos = \App\Models\Carrera::pluck('int_cupo', 'id_carrera');
        $cuposUsados = $cupos->map(fn() => 0)->toArray(); // contador dinámico

        // 2. Cargar postulantes con evaluaciones+detalles y su
        //    carrera_1 / carrera_2 (última inscripción)
        $postulantes = Postulante::with([
                'evaluaciones.detalles',
                'inscripciones' => fn($q) => $q->latest('fch_inscripcion')->limit(1)->with('carreras'),
            ])
            ->get();

        // 3. Calcular nota_final y aprobado para cada postulante
        $calculados = $postulantes->map(function (Postulante $p) {
            [$aprobado, $notaFinal] = $this->evaluarPostulante($p);

            $inscripcion = $p->inscripciones->first();
            $carrera1 = $inscripcion?->carreras->firstWhere('pivot.int_prioridad', 1);
            $carrera2 = $inscripcion?->carreras->firstWhere('pivot.int_prioridad', 2);

            return [
                'id_postulante'   => $p->id_postulante,
                'nota_final'      => $notaFinal,
                'aprobado'        => $aprobado,
                'id_carrera_1'    => $carrera1?->id_carrera,
                'id_carrera_2'    => $carrera2?->id_carrera,
                'txt_carrera_1'   => $carrera1?->txt_nombre,
                'txt_carrera_2'   => $carrera2?->txt_nombre,
            ];
        });

        // 4. Separar aprobados (ordenados por nota_final desc) de no aprobados
        $aprobados = $calculados->filter(fn($c) => $c['aprobado'])
            ->sortByDesc('nota_final')
            ->values();

        $noAprobados = $calculados->filter(fn($c) => ! $c['aprobado'])->values();

        // 5. Asignar resultado y carrera según ranking + cupos
        $resultados = collect();

        foreach ($aprobados as $i => $c) {
            $ranking = $i + 1; // 1-indexed

            if ($ranking <= self::CUPO_ADMISION) {
                // Top 300: intenta carrera_1, luego carrera_2
                [$idCarrera, $txtCarrera, $resultado] = $this->intentarAdmitir(
                    $c, $cupos, $cuposUsados, intentarPrimero: 1
                );
            } else {
                // Resto de aprobados: directo a carrera_2
                [$idCarrera, $txtCarrera, $resultado] = $this->intentarAdmitir(
                    $c, $cupos, $cuposUsados, intentarPrimero: 2
                );
            }

            $resultados->put($c['id_postulante'], [
                'id_postulante'        => $c['id_postulante'],
                'nota_final'           => $c['nota_final'],
                'aprobado'             => true,
                'ranking'              => $ranking,
                'txt_resultado'        => $resultado,
                'id_carrera_admitida'  => $idCarrera,
                'txt_carrera_admitida' => $txtCarrera,
            ]);
        }

        foreach ($noAprobados as $c) {
            $resultados->put($c['id_postulante'], [
                'id_postulante'        => $c['id_postulante'],
                'nota_final'           => $c['nota_final'],
                'aprobado'             => false,
                'ranking'              => null,
                'txt_resultado'        => 'NO_APROBADO',
                'id_carrera_admitida'  => null,
                'txt_carrera_admitida' => null,
            ]);
        }

        // Cachear para que resumenCupos() pueda exponerlos sin
        // recalcular todo de nuevo.
        $this->ultimosCupos = $cupos;
        $this->ultimosCuposUsados = $cuposUsados;

        return $resultados;
    }

    /**
     * Devuelve un resumen de cupos por carrera: total, usados y
     * disponibles, tras ejecutar (o reutilizar) calcularResultados().
     *
     * @return Collection<int, array{id_carrera:int, txt_nombre:string, usado:int, total:int, disponible:int, lleno:bool}>
     */
    public function resumenCupos(): Collection
    {
        if ($this->ultimosCupos === null || $this->ultimosCuposUsados === null) {
            $this->calcularResultados();
        }

        return \App\Models\Carrera::orderBy('id_carrera')->get()
            ->map(function ($carrera) {
                $total = $this->ultimosCupos->get($carrera->id_carrera, 0);
                $usado = $this->ultimosCuposUsados[$carrera->id_carrera] ?? 0;

                return [
                    'id_carrera'  => $carrera->id_carrera,
                    'txt_nombre'  => $carrera->txt_nombre,
                    'usado'       => $usado,
                    'total'       => $total,
                    'disponible'  => max(0, $total - $usado),
                    'lleno'       => $usado >= $total,
                ];
            });
    }

    /**
     * Resultado de admisión para UN solo postulante (conveniencia
     * para PostulanteSelfController::me()). Internamente recalcula
     * todo el ranking — aceptable para el volumen de datos actual,
     * ya que no se persiste nada.
     */
    public function resultadoDe(int $idPostulante): ?array
    {
        return $this->calcularResultados()->get($idPostulante);
    }

    /**
     * Determina si el postulante está APROBADO (12 notas completas,
     * todas >= 60) y calcula su nota_final (promedio general de las
     * 12 notas, equivalente al promedio de los 4 num_promedio_materia).
     *
     * nota_final se calcula SIEMPRE que existan las 12 notas
     * completas, sin importar si todas son >= 60 — así, los
     * reprobados también muestran su promedio general (solo
     * 'aprobado' determina si entran al ranking de admisión).
     *
     * @return array{0: bool, 1: float|null} [aprobado, nota_final]
     */
    private function evaluarPostulante(Postulante $p): array
    {
        $evaluaciones = $p->evaluaciones;

        // Debe tener exactamente 3 evaluaciones (exámenes 1, 2, 3)
        if ($evaluaciones->count() !== 3) {
            return [false, null];
        }

        // Cada evaluación debe tener exactamente 4 detalles (materias)
        foreach ($evaluaciones as $ev) {
            if ($ev->detalles->count() !== 4) {
                return [false, null];
            }
        }

        $todasLasNotas = $evaluaciones->flatMap(fn($ev) => $ev->detalles->pluck('num_nota'));

        if ($todasLasNotas->count() !== 12) {
            return [false, null];
        }

        // nota_final = promedio general de las 12 notas (= promedio
        // de los 4 num_promedio_materia). Se calcula siempre, sin
        // importar si aprueba o no.
        $notaFinal = round($todasLasNotas->sum() / 12, 2);

        // Aprobado = TODAS las 12 notas individuales >= 60
        $aprobado = $todasLasNotas->every(fn($nota) => (float) $nota >= 60);

        return [$aprobado, $notaFinal];
    }

    /**
     * Intenta admitir al postulante en carrera_1 o carrera_2 según
     * disponibilidad de cupo, empezando por la indicada en
     * $intentarPrimero (1 o 2).
     *
     * @return array{0: int|null, 1: string|null, 2: string} [id_carrera, txt_carrera, resultado]
     */
    private function intentarAdmitir(
        array $c,
        Collection $cupos,
        array &$cuposUsados,
        int $intentarPrimero
    ): array {
        $orden = $intentarPrimero === 1 ? [1, 2] : [2, 1];

        // Si solo debe intentar carrera_2 (resto de aprobados, según
        // la regla: van DIRECTO a carrera_2, sin pasar por carrera_1)
        if ($intentarPrimero === 2) {
            $orden = [2];
        }

        foreach ($orden as $prioridad) {
            $idCarrera  = $c["id_carrera_{$prioridad}"];
            $txtCarrera = $c["txt_carrera_{$prioridad}"];

            if (! $idCarrera) {
                continue; // no eligió esta opción
            }

            $cupoTotal = $cupos->get($idCarrera, 0);
            $usado     = $cuposUsados[$idCarrera] ?? 0;

            if ($usado < $cupoTotal) {
                $cuposUsados[$idCarrera] = $usado + 1;
                $resultado = $prioridad === 1 ? 'ADMITIDO_CARRERA_1' : 'ADMITIDO_CARRERA_2';
                return [$idCarrera, $txtCarrera, $resultado];
            }
        }

        return [null, null, 'NO_ADMITIDO'];
    }
}