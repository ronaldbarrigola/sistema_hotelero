<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class EstadoReserva extends Model
{
    protected $table="res_estado_huesped";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    //Relacion 1 a muchos
    public function huespedes()
    {
       return $this->hasMany(Huesped::class,'estado_huesped_id','id');
    }

}
