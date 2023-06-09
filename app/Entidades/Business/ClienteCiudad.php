<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class ClienteCiudad extends Model
{
    protected $table="cli_ciudad";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'pais_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

}
