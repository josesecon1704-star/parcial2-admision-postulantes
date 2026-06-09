<?php

namespace App\Models;

// DESTINO: app/Models/InscripcionCarrera.php
// Tabla pivote: tbl_inscripcion_carrera
use Illuminate\Database\Eloquent\Model;

class InscripcionCarrera extends Model
{
    protected $table      = 'tbl_inscripcion_carrera';
    public    $timestamps = false;
    public    $incrementing = false;

    protected $fillable = [
        'id_inscripcion',
        'id_carrera',
        'int_prioridad',
    ];
}
