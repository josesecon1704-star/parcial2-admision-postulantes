<?php

namespace App\Models;

// ============================================================
// DESTINO: app/Models/Rol.php
//
// Mapea exactamente la tabla tbl_rol de tu PostgreSQL:
//   id_rol | txt_nombre | txt_descripcion | fch_creacion
// ============================================================

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    // ── Tabla real en PostgreSQL ────────────────────────────
    protected $table = 'tbl_rol';

    // ── PK no estándar (no es 'id') ─────────────────────────
    protected $primaryKey = 'id_rol';

    // ── Sin timestamps automáticos de Laravel (created_at/updated_at)
    // La tabla usa fch_creacion que manejamos manualmente
    public $timestamps = false;

    // ── Campos asignables masivamente ───────────────────────
    protected $fillable = [
        'txt_nombre',
        'txt_descripcion',
        'fch_creacion',
    ];

    protected $casts = [
        'fch_creacion' => 'datetime',
    ];

    // ── Relaciones ──────────────────────────────────────────
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }
}
