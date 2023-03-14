<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    protected $table="bas_usuario_rol";
    protected $primaryKey="id";
    public $timestamps=false;
    //
    protected $fillable=[
        'usuario_id',
        'rol_id',
        'fecha_alta',
        'usuario_alta_id',
        'estado'
    ];
}
