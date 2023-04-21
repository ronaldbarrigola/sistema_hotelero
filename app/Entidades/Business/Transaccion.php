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

    public function delete() //Eliminacion logica
    {

        $this->transaccionPago()->each(function ($transaccionPago) {//Eliminacion logica en cascada
            $transaccionPago->delete();
        });
        $this->transaccionDetalle()->each(function ($transaccionDetalle) {//Eliminacion logica en cascada
            $transaccionDetalle->delete();
        });

        $this->estado = false;
        $this->save();
    }

    //Relacion uno a muchos (Inversa)
    public function cargo()
    {
       return $this->belongsTo(Transaccion::class,'cargo_id','id');
    }

    //Relacion uno a muchos (Inversa)
    public function reserva()
    {
       return $this->belongsTo(Reserva::class,'reserva_id','id');
    }

    //Relacion 1 a muchos
    public function transaccionDetalle()
    {
       return $this->hasMany(TransaccionDetalle::class,'transaccion_id','id');
    }

    //Relacion 1 a muchos
    public function transaccionPago()
    {
       return $this->hasMany(TransaccionPago::class,'transaccion_id','id');
    }
}
