<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    protected $table="res_paquete";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'fecha_ini',
        'fecha_fin',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
