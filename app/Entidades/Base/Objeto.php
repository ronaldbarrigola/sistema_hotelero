<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Objeto extends Model
{
    protected $table="bas_objeto";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        "nombre",
        "descripcion",
        "contenedor",
        "icono",
        "texto",
        "estado"
    ];
    
   
}
