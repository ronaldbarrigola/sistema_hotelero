<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class EstadoReserva extends Model
{
    protected $table="res_estado_reserva";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'color',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
