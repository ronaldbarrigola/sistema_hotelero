<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class TransaccionPago extends Model
{
    protected $table="con_transaccion_pago";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'pago_id',
        'transaccion_id',
        'detalle',
        'monto',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
