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
        'detalle',
        'num_adulto',
        'num_nino',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
