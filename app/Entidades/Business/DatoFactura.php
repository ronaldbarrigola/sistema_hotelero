<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class DatoFactura extends Model
{
    protected $table="con_datofactura";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nit',
        'nombre',
        'celular',
        'email',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
