<?php

namespace App\Models;

// ============================================================
// DESTINO: app/Models/Requisito.php
//
// Tabla real: tbl_requisito
// Columnas:   id_requisito | txt_descripcion_requisito
//
// Tabla pivote: tbl_requisito_postulante
//   id_requisito | id_postulante | fch_presentacion
// ============================================================

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Requisito extends Model
{
    protected $table      = 'tbl_requisito';
    protected $primaryKey = 'id_requisito';
    public    $timestamps = false;

    protected $fillable = [
        'txt_descripcion_requisito',
    ];

    // ── Relaciones ───────────────────────────────────────────
    public function postulantes(): BelongsToMany
    {
        return $this->belongsToMany(
            Postulante::class,
            'tbl_requisito_postulante', // tabla pivote real
            'id_requisito',             // FK hacia esta tabla
            'id_postulante'             // FK hacia postulante
        )->withPivot('fch_presentacion');
    }
}
