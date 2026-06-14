<?php
namespace App\Models;

// Tabla: tbl_gestion — int_año | txt_periodo
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gestion extends Model
{
    protected $table      = 'tbl_gestion';
    protected $primaryKey = 'id_gestion';
    public    $timestamps = false;

    protected $fillable = ['int_año', 'txt_periodo'];

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'id_gestion', 'id_gestion');
    }
}
