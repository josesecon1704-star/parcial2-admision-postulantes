<?php

namespace App\Models;

// ============================================================
// Tabla real: tbl_postulante
// Columnas:
//   id_postulante | txt_ci | txt_nombre | txt_telefono |
//   txt_correo    | fch_nacimiento | chr_sexo |
//   txt_direccion | txt_colegio | txt_ciudad
//
// Relaciones requeridas por PostulanteService::formatear():
//   - requisitos()    → BelongsToMany via tbl_requisito_postulante
//   - inscripciones() → HasMany via tbl_inscripcion
//   - evaluaciones()  → HasMany via tbl_evaluacion
// ============================================================

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Postulante extends Model
{
    protected $table      = 'tbl_postulante';
    protected $primaryKey = 'id_postulante';
    public    $timestamps = false;

    protected $fillable = [
        'txt_ci',
        'txt_nombre',
        'txt_telefono',
        'txt_correo',
        'fch_nacimiento',
        'chr_sexo',
        'txt_direccion',
        'txt_colegio',
        'txt_ciudad',
    ];

    protected $casts = [
        'fch_nacimiento' => 'date',
    ];

    // ── Relaciones ───────────────────────────────────────────

    /**
     * Requisitos entregados por el postulante.
     * Tabla pivote real: tbl_requisito_postulante
     * Necesario para loadCount('requisitos') en PostulanteService
     */
    public function requisitos(): BelongsToMany
    {
        return $this->belongsToMany(
            Requisito::class,           // Modelo relacionado
            'tbl_requisito_postulante', // Tabla pivote real en PostgreSQL
            'id_postulante',            // FK de esta tabla en el pivote
            'id_requisito'              // FK de la otra tabla en el pivote
        )->withPivot('fch_presentacion');
    }

    /**
     * Inscripciones del postulante.
     * Necesario para verificar inscripciones activas antes de eliminar
     * y para mostrar la última inscripción en show()
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(
            Inscripcion::class,
            'id_postulante', // FK en tbl_inscripcion
            'id_postulante'  // PK en tbl_postulante
        );
    }

    /**
     * Evaluaciones (exámenes) del postulante.
     */
    public function evaluaciones(): HasMany
    {
        return $this->hasMany(
            Evaluacion::class,
            'id_postulante',
            'id_postulante'
        );
    }

    // ── Accessor: edad calculada desde fch_nacimiento ────────
    public function getEdadAttribute(): int
    {
        return $this->fch_nacimiento
            ? $this->fch_nacimiento->age
            : 0;
    }
}