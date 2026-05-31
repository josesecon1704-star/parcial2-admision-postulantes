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
        'num_nota_1',  // Examen 1 — entre 0 y 100
        'num_nota_2',  // Examen 2 — entre 0 y 100
        'num_nota_3',  // Examen 3 — entre 0 y 100
        // num_promedio_materia → NO va aquí, es GENERATED ALWAYS en PostgreSQL
    ];

    protected $casts = [
        'num_nota_1'           => 'decimal:2',
        'num_nota_2'           => 'decimal:2',
        'num_nota_3'           => 'decimal:2',
        'num_promedio_materia' => 'decimal:2',
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
