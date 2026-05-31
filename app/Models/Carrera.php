<?php
namespace App\Models;
// DESTINO: app/Models/Carrera.php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Carrera extends Model
{
    protected $table      = 'tbl_carrera';
    protected $primaryKey = 'id_carrera';
    public    $timestamps = false;

    protected $fillable = ['txt_nombre', 'int_cupo'];

    public function inscripciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Inscripcion::class,
            'tbl_inscripcion_carrera',
            'id_carrera',
            'id_inscripcion'
        )->withPivot('int_prioridad');
    }
}
