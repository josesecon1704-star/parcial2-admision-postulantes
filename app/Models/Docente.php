<?php

namespace App\Models;
// DESTINO: app/Models/Docente.php
// Tabla: tbl_docente
// Columnas: id_docente | txt_ci | txt_nombre | txt_telefono | txt_correo
// UNIQUE: txt_ci, txt_correo
// Trigger T4: bloquea asignación si docente ya tiene 4 grupos
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Docente extends Model
{
    protected $table      = 'tbl_docente';
    protected $primaryKey = 'id_docente';
    public    $timestamps = false;

    protected $fillable = [
        'txt_ci',
        'txt_nombre',
        'txt_telefono',
        'txt_correo',
        'txt_usuario'
    ];

    // Profesiones con título y universidad (tabla pivote con atributos)
    public function profesiones(): BelongsToMany
    {
        return $this->belongsToMany(
            Profesion::class,
            'tbl_docente_profesion',
            'id_docente',
            'id_profesion'
        )->withPivot('txt_titulo', 'txt_universidad', 'fch_emicion');
    }

    // Formaciones académicas (Maestría, Diplomado, etc.)
    public function formaciones(): BelongsToMany
    {
        return $this->belongsToMany(
            FormacionAcademica::class,
            'tbl_docente_formacion',
            'id_docente',
            'id_formacion'
        );
    }

    // Contrataciones históricas
    public function contrataciones(): HasMany
    {
        return $this->hasMany(Contratacion::class, 'id_docente', 'id_docente');
    }

    // Contratación activa actual
    public function contratacionActiva(): HasMany
    {
        return $this->hasMany(Contratacion::class, 'id_docente', 'id_docente')
            ->where('txt_estado', 'ACTIVO');
    }

    // Grupos asignados (via tbl_asignacion_docente)
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionDocente::class, 'id_docente', 'id_docente');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
