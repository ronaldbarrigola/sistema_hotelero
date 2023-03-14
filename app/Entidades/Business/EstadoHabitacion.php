<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class EstadoHabitacion extends Model
{
    protected $table="gob_estado_habitacion";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
