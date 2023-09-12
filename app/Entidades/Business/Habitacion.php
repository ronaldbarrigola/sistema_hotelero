<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table="gob_habitacion";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'agencia_id',
        'tipo_habitacion_id',
        'estado_habitacion_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'num_habitacion',
        'piso',
        'descripcion',
        'precio',
        'imagen',
        'estilo',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    //Relacion 1 a muchos
    public function reservas()
    {
       return $this->hasMany(Reserva::class,'habitacion_id','id');
    }
}
