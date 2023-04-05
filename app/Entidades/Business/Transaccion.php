<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table="con_transaccion";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'hotel_producto_id',
        'venta_id',
        'reserva_id',
        'detalle',
        'tipo_transaccion_id',
        'factor',
        'cantidad',
        'precio_unidad',
        'descuento_porcentaje',
        'descuento',
        'monto',
        'usuario_alta_id',
        'usuario_modif_id',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
