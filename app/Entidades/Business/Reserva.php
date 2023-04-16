<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table="res_reserva";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'cliente_id',
        'estado_reserva_id',
        'paquete_id',
        'habitacion_id',
        'procedencia_ciudad_id',
        'procedencia_pais_id',
        'procedencia_ciudad_id',
        'grupo_id',
        'servicio_id',
        'motivo_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'fecha_ini',
        'fecha_fin',
        'hora_ini',
        'hora_fin',
        'detalle',
        'num_adulto',
        'num_nino',
        'cantidad',
        'precio_unidad',
        'descuento_porcentaje',
        'descuento',
        'monto',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    //Relacion 1 a muchos (Inversa)
    public function servicio()
    {
        return $this->belongsTo(Servicio::class,'servicio_id','id');
    }

    //Relacion 1 a muchos
    public function cargos()
    {
       return $this->hasMany(Cargo::class,'reserva_id','id');
    }

}
