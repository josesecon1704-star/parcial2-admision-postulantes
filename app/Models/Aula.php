<?php
namespace App\Models;

// Tabla: tbl_aula — id_aula | int_piso | txt_nro_aula
// UNIQUE: (int_piso, txt_nro_aula)
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    protected $table      = 'tbl_aula';
    protected $primaryKey = 'id_aula';
    public    $timestamps = false;

    protected $fillable = ['int_piso', 'txt_nro_aula'];
}
