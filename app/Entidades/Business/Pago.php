<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table="con_pago";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'nombre',
        'nit',
        'email',
        'detalle',
        'agencia_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
