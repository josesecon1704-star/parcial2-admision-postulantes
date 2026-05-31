<?php
namespace App\Models;
// DESTINO: app/Models/Horario.php
// Tabla: tbl_horario
// Columnas: id_horario | txt_dia_semana | tm_hora_inicio | tm_hora_final | id_turno
// CHECK: tm_hora_final > tm_hora_inicio  (en PostgreSQL)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $table      = 'tbl_horario';
    protected $primaryKey = 'id_horario';
    public    $timestamps = false;

    protected $fillable = [
        'txt_dia_semana',
        'tm_hora_inicio',
        'tm_hora_final',
        'id_turno',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class, 'id_turno', 'id_turno');
    }
}
