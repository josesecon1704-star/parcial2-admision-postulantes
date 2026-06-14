<?php
namespace App\Models;
// Tabla: tbl_turno — id_turno | txt_turno
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turno extends Model
{
    protected $table      = 'tbl_turno';
    protected $primaryKey = 'id_turno';
    public    $timestamps = false;

    protected $fillable = ['txt_turno'];

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class, 'id_turno', 'id_turno');
    }
}
