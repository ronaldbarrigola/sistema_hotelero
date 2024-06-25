<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table="res_grupo";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'color',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    //Relacion uno a muchos
    public function reservas()
    {
       return $this->hasMany(Reserva::class,'reserva_id','id');
    }

}
