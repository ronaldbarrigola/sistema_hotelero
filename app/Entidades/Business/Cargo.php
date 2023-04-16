<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table="con_cargo";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'reserva_id',
        'detalle',
        'reserva_base',
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
