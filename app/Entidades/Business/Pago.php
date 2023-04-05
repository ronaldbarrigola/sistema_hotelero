<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table="con_pago";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'comprobante_pago_id',
        'forma_pago_id',
        'venta_id',
        'reserva_id',
        'cargo_id',
        'detalle',
        'importe',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
