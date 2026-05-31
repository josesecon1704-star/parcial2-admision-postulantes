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
     */
    public function listar(array $filtros, int $perPage = 15): LengthAwarePaginator
    {
        $query = Postulante::query();

        // CU-09: Buscar por CI, nombre o correo
        if (! empty($filtros['buscar'])) {
            $b = $filtros['buscar'];
            $query->where(function ($q) use ($b) {
                $q->where('txt_ci',     'ilike', "%{$b}%")
                  ->orWhere('txt_nombre','ilike', "%{$b}%")
                  ->orWhere('txt_correo','ilike', "%{$b}%");
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
     * Formatear un postulante para la respuesta JSON
     */
    public function formatear(Postulante $postulante): array
{
    // Buscamos la última inscripción y cargamos las carreras de una vez
    $inscripcion = $postulante->inscripciones()
        ->with('carreras')
        ->latest('fch_inscripcion')
        ->first();

    // Filtramos las carreras por prioridad usando el pivot
    $carrera1 = $inscripcion ? $inscripcion->carreras->firstWhere('pivot.int_prioridad', 1) : null;
    $carrera2 = $inscripcion ? $inscripcion->carreras->firstWhere('pivot.int_prioridad', 2) : null;

    return [
        'id_postulante' => $postulante->id_postulante,
        'txt_nombre'    => $postulante->txt_nombre,
        'txt_ci'        => $postulante->txt_ci,
        'carrera_1'     => $carrera1 ? $carrera1->txt_nombre : 'N/A',
        'carrera_2'     => $carrera2 ? $carrera2->txt_nombre : '-',
    ];
}


}
