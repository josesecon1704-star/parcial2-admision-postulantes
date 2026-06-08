<?php
namespace App\Models;
// DESTINO: app/Models/DetalleEvaluacion.php
// Tabla: tbl_detalle_evaluacion
// PK compuesta: (id_evaluacion, id_materia)
// IMPORTANTE: num_promedio_materia es GENERATED ALWAYS (columna calculada en PostgreSQL)
//             NO incluirla en $fillable — PostgreSQL la calcula sola:
//             (num_nota_1 + num_nota_2 + num_nota_3) / 3
// CHECK: notas entre 0 y 100 para las 3 notas
// Trigger: trg_auditar_notas registra INSERT/UPDATE/DELETE en tbl_auditoria
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleEvaluacion extends Model
{
    protected $table   = 'tbl_detalle_evaluacion';
    public    $timestamps = false;
    // PK compuesta — Eloquent no la maneja directamente
    protected $primaryKey = null;
    public    $incrementing = false;

    protected $fillable = [
        'id_evaluacion',
        'id_materia',
        'num_nota',  // Una sola nota por materia por examen (0-100)
    ];

    protected $casts = [
        'num_nota' => 'decimal:2',
    ];

    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'id_materia', 'id_materia');
    }
}
