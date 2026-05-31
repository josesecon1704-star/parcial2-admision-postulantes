<?php
namespace App\Models;
// DESTINO: app/Models/Evaluacion.php
// Tabla: tbl_evaluacion
// Columnas: id_evaluacion | int_nro_examen | fch_examen | id_postulante
// Regla: Solo 3 exámenes por postulante (int_nro_examen: 1, 2 o 3)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluacion extends Model
{
    protected $table      = 'tbl_evaluacion';
    protected $primaryKey = 'id_evaluacion';
    public    $timestamps = false;

    protected $fillable = [
        'int_nro_examen',
        'fch_examen',
        'id_postulante',
    ];

    protected $casts = ['fch_examen' => 'date'];

    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class, 'id_postulante', 'id_postulante');
    }

    // 4 filas: una por materia (Computación, Matemáticas, Inglés, Física)
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleEvaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }
}
