<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    protected $table="res_huesped";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'persona_id',
        'reserva_id',
        'estado_huesped_id',
        'fecha',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    public function delete()//Eliminacion logica
    {
        $this->estado = false;
        $this->save();
    }

    //Relacion 1 a muchos (Inversa)
    public function estadoHuesped()
    {
    return $this->belongsTo(EstadoHuesped::class,'estado_huesped_id','id');
    }

    //Relacion 1 a muchos (Inversa)
    public function reserva()
    {
       return $this->belongsTo(Reserva::class,'reserva_id','id');
    }

}
