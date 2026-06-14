<?php
namespace App\Models;

// Tabla: tbl_asignacion_docente
// Columnas: id_asignacion | id_docente | id_materia | id_grupo
// UNIQUE: (id_grupo, id_materia)
// Trigger T4: bloquea si docente ya tiene 4 grupos distintos
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsignacionDocente extends Model
{
    protected $table      = 'tbl_asignacion_docente';
    protected $primaryKey = 'id_asignacion';
    public    $timestamps = false;

    protected $fillable = ['id_docente', 'id_materia', 'id_grupo'];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'id_grupo', 'id_grupo');
    }
}
