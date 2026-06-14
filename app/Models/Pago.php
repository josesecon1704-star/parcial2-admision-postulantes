<?php
namespace App\Models;
// Tabla: tbl_pago
// UNIQUE: id_inscripcion (máximo 1 pago por inscripción)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table      = 'tbl_pago';
    protected $primaryKey = 'id_pago';
    public    $timestamps = false;

    protected $fillable = [
        'num_monto',
        'fch_pago',
        'txt_estado',
        'txt_metodo',
        'txt_referencia',
        'id_inscripcion',
    ];

    protected $casts = [
        'fch_pago'   => 'datetime',
        'num_monto'  => 'decimal:2',
    ];

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion', 'id_inscripcion');
    }
}
