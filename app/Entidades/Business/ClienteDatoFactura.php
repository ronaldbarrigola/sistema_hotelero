<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class ClienteDatoFactura extends Model
{
    protected $table="con_cliente_datofactura";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'cliente_id',
        'datofactura_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
