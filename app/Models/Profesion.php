<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Profesion extends Model
{
    protected $table      = 'tbl_profesion';
    protected $primaryKey = 'id_profesion';
    public    $timestamps = false;
    protected $fillable   = ['txt_descripcion'];
}
