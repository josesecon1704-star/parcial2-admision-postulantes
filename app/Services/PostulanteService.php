<?php

namespace App\Services;

// ============================================================
// DESTINO: app/Services/PostulanteService.php
//
// Lógica de negocio separada del controlador:
//   - formatear respuesta de postulante
//   - calcular estado de requisitos
//   - calcular estado de inscripción
// ============================================================

use App\Models\Postulante;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostulanteService
{
    /**
     * Listar postulantes con filtros y paginación (CU-10)
     *
     * IMPORTANTE: precarga (eager load) la última inscripción con
     * sus relaciones (carreras, grupo, gestion, pago) y el conteo
     * de requisitos en UNA sola tanda de queries adicionales, sin
     * importar cuántos postulantes haya (evita el problema N+1 que
     * causaba timeouts con per_page=1000).
     */
    public function listar(array $filtros, int $perPage = 15, bool $conRelaciones = false): LengthAwarePaginator
    {
        $query = Postulante::query();

        // CU-09: Buscar por CI, nombre o correo
        if (! empty($filtros['buscar'])) {
            $b = $filtros['buscar'];
            $query->where(function ($q) use ($b) {
                $q->where('txt_ci',     'ilike', "%{$b}%")
                    ->orWhere('txt_nombre', 'ilike', "%{$b}%")
                    ->orWhere('txt_correo', 'ilike', "%{$b}%");
            });
        }

        // Filtro por ciudad
        if (! empty($filtros['ciudad'])) {
            $query->where('txt_ciudad', 'ilike', "%{$filtros['ciudad']}%");
        }

        // Filtro por sexo
        if (! empty($filtros['sexo'])) {
            $query->where('chr_sexo', strtoupper($filtros['sexo']));
        }

        // Eager load: última inscripción + sus relaciones, en lote
        // (no por-fila). 'inscripciones' se ordena por fecha desc;
        // tomamos solo la primera en formatear() con ->first().
        $query->with([
            'inscripciones' => fn($q) => $q->latest('fch_inscripcion')
                ->with(['carreras', 'grupo', 'gestion', 'pago']),
        ]);

        if ($conRelaciones) {
            $query->withCount('requisitos');
        }

        return $query
            ->orderBy('txt_nombre', 'asc')
            ->paginate($perPage);
    }

    /**
     * Crear postulante (CU-06)
     */
    public function crear(array $datos): Postulante
    {
        return Postulante::create($datos);
    }

    /**
     * Actualizar postulante (CU-07)
     * Solo actualiza los campos que vienen en el request (PATCH parcial)
     */
    public function actualizar(Postulante $postulante, array $datos): Postulante
    {
        // Filtrar nulls explícitos — conservar solo los campos enviados
        $datos = array_filter($datos, fn($v) => ! is_null($v));
        $postulante->update($datos);
        return $postulante->fresh();
    }

    /**
     * Eliminar postulante (CU-08)
     * Eliminación física — el trigger de auditoría en PostgreSQL
     * registrará el DELETE automáticamente en tbl_auditoria
     *
     * @throws \Exception si tiene inscripciones activas
     */
    public function eliminar(Postulante $postulante): void
    {
        // Verificar que no tenga inscripciones activas antes de borrar
        $tieneInscripciones = $postulante->inscripciones()
            ->whereIn('txt_estado_inscripcion', ['PENDIENTE', 'PROCESADO'])
            ->exists();

        if ($tieneInscripciones) {
            throw new \Exception(
                'No se puede eliminar el postulante porque tiene inscripciones activas.'
            );
        }

        $postulante->delete();
    }

    /**
     * Formatear un postulante para la respuesta JSON.
     *
     * @param array|null $resultadoAdmision Resultado precalculado por
     *        AdmisionService::calcularResultados() para este postulante
     *        (nota_final, txt_resultado, txt_carrera_admitida, etc.).
     *        Se pasa desde fuera para evitar recalcular el ranking
     *        completo por cada fila.
     */
    public function formatear(Postulante $postulante, bool $conRelaciones = false, ?array $resultadoAdmision = null): array
    {
        $inscripcion = $postulante->inscripciones->first();

        $carrera1 = $inscripcion ? $inscripcion->carreras->firstWhere('pivot.int_prioridad', 1) : null;
        $carrera2 = $inscripcion ? $inscripcion->carreras->firstWhere('pivot.int_prioridad', 2) : null;

        $grupo   = $inscripcion?->grupo;
        $gestion = $inscripcion?->gestion;

        $data = [
            'id_postulante'  => $postulante->id_postulante,
            'txt_ci'         => $postulante->txt_ci,
            'txt_nombre'     => $postulante->txt_nombre,
            'txt_correo'     => $postulante->txt_correo,
            'txt_telefono'   => $postulante->txt_telefono,
            'fch_nacimiento' => $postulante->fch_nacimiento?->format('Y-m-d'),
            'chr_sexo'       => $postulante->chr_sexo,
            'txt_direccion'  => $postulante->txt_direccion,
            'txt_colegio'    => $postulante->txt_colegio,
            'txt_ciudad'     => $postulante->txt_ciudad,
            'carrera_1'      => $carrera1?->txt_nombre ?? 'N/A',
            'carrera_2'      => $carrera2?->txt_nombre ?? '-',
            'grupo'          => $grupo ? [
                'id_grupo'   => $grupo->id_grupo,
                'txt_nombre' => $grupo->txt_nombre,
            ] : null,
            'gestion'        => $gestion ? [
                'id_gestion'  => $gestion->id_gestion,
                'int_año'     => $gestion->int_año,
                'txt_periodo' => $gestion->txt_periodo,
            ] : null,
        ];

        if ($conRelaciones) {
            $data['requisitos_entregados'] = $postulante->requisitos_count
                ?? $postulante->requisitos()->count();

            $data['ultima_inscripcion'] = $inscripcion ? [
                'id_inscripcion'         => $inscripcion->id_inscripcion,
                'txt_estado_inscripcion' => $inscripcion->txt_estado_inscripcion,
                'fch_inscripcion'        => $inscripcion->fch_inscripcion,
                'pago' => $inscripcion->pago ? [
                    'num_monto'  => (float) $inscripcion->pago->num_monto,
                    'txt_estado' => $inscripcion->pago->txt_estado,
                ] : null,
            ] : null;
        }

        // Resultado de admisión (nota final, aprobado, carrera admitida)
        if ($resultadoAdmision) {
            $data['admision'] = [
                'nota_final'           => $resultadoAdmision['nota_final'],
                'aprobado'             => $resultadoAdmision['aprobado'],
                'ranking'              => $resultadoAdmision['ranking'],
                'txt_resultado'        => $resultadoAdmision['txt_resultado'],
                'id_carrera_admitida'  => $resultadoAdmision['id_carrera_admitida'],
                'txt_carrera_admitida' => $resultadoAdmision['txt_carrera_admitida'],
            ];
        }

        return $data;
    }
}