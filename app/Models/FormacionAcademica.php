<?php
namespace App\Models;
// DESTINO: app/Models/FormacionAcademica.php
use Illuminate\Database\Eloquent\Model;
class FormacionAcademica extends Model
{
    protected $table      = 'tbl_formacion_academica';
    protected $primaryKey = 'id_formacion';
    public    $timestamps = false;
    protected $fillable   = ['txt_nombre', 'txt_tipo', 'txt_institucion'];
}
