<?php
namespace App\Models;
// DESTINO: app/Models/Grupo.php
// Tabla: tbl_grupo
// Columnas: id_grupo | txt_nombre | int_cantidad_estudiantes |
//           int_capacidad_maxma | id_inscripcion
// NOTA: el campo se llama int_capacidad_maxma (sin 'i' al final — error tipográfico en la BD, respetar)
// Trigger T3 crea grupos automáticamente con CEIL(inscritos/80)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table      = 'tbl_grupo';
    protected $primaryKey = 'id_grupo';
    public    $timestamps = false;

    protected $fillable = [
        'txt_nombre',
        'int_cantidad_estudiantes',
        'int_capacidad_maxma',   // respetar el typo de la BD
        'id_inscripcion',
    ];

    // ── Relaciones ───────────────────────────────────────────
    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion', 'id_inscripcion');
    }

    // Horarios y aulas asignadas al grupo (tabla pivote tbl_grupo_horario)
    public function horarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Horario::class,
            'tbl_grupo_horario',
            'id_grupo',
            'id_horario'
        )->withPivot('id_aula');
    }

    // Docentes asignados al grupo (via tbl_asignacion_docente)
    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionDocente::class, 'id_grupo', 'id_grupo');
    }

    // ── Helper: cupos disponibles ────────────────────────────
    public function getCuposDisponiblesAttribute(): int
    {
        return $this->int_capacidad_maxma - $this->int_cantidad_estudiantes;
    }
}
