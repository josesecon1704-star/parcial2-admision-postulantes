<?php
namespace App\Models;
// DESTINO: app/Models/Materia.php
// Tabla: tbl_materia — id_materia | txt_nombre
// UNIQUE: txt_nombre
// Regla de negocio: exactamente 4 materias
//   Computación | Matemáticas | Inglés | Física
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Materia extends Model
{
    protected $table      = 'tbl_materia';
    protected $primaryKey = 'id_materia';
    public    $timestamps = false;

    protected $fillable = ['txt_nombre'];

    public function detallesEvaluacion(): HasMany
    {
        return $this->hasMany(DetalleEvaluacion::class, 'id_materia', 'id_materia');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(AsignacionDocente::class, 'id_materia', 'id_materia');
    }
}
