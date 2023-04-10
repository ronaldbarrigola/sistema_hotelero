<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class TipoTransaccion extends Model
{
    protected $table="con_tipo_transaccion";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'factor',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
