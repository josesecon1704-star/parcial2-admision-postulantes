<?php
namespace App\Models;
// Tabla: tbl_contratacion
// Columnas: id_contratacion | fch_contrato | num_salario |
//           txt_estado | txt_observacion | id_docente | id_usuario
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contratacion extends Model
{
    protected $table      = 'tbl_contratacion';
    protected $primaryKey = 'id_contratacion';
    public    $timestamps = false;

    protected $fillable = [
        'fch_contrato',
        'num_salario',
        'txt_estado',
        'txt_observacion',
        'id_docente',
        'id_usuario',
    ];

    protected $casts = [
        'fch_contrato' => 'date',
        'num_salario'  => 'decimal:2',
    ];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
