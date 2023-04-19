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
        'cargo_id',
        'reserva_id',
        'detalle',
        'cantidad',
        'precio_unidad',
        'descuento_porcentaje',
        'descuento',
        'monto',
        'transaccion_base',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

     //Relacion uno a muchos (Inversa)
     public function reserva()
     {
         return $this->belongsTo(Reserva::class,'reserva_id','id');
     }
}
