<?php

namespace App\Models;

// ============================================================
// DESTINO: app/Models/Inscripcion.php
//
// Tabla real: tbl_inscripcion
// Columnas:
//   id_inscripcion | fch_inscripcion | txt_estado_inscripcion |
//   id_postulante  | id_gestion
//
// Estados posibles: PENDIENTE → PROCESADO
// Trigger activo:
//   trg_validar_requisitos_admision → bloquea si falta documentación
//   trg_ejecutar_algoritmo_grupos   → crea grupos al pasar a PROCESADO
// ============================================================

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inscripcion extends Model
{
    protected $table      = 'tbl_inscripcion';
    protected $primaryKey = 'id_inscripcion';
    public    $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'id_gestion',
        'txt_estado_inscripcion',
        'fch_inscripcion',
    ];

    protected $casts = [
        'fch_inscripcion' => 'datetime',
    ];

    // ── Relaciones ───────────────────────────────────────────
    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class, 'id_gestion', 'id_gestion');
    }

    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class, 'id_inscripcion', 'id_inscripcion');
    }

    // Carreras elegidas con prioridad (1ra y 2da opción)
    public function carreras(): BelongsToMany
    {
        return $this->belongsToMany(
            Carrera::class,
            'tbl_inscripcion_carrera',
            'id_inscripcion',
            'id_carrera'
        )->withPivot('int_prioridad');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }
}
