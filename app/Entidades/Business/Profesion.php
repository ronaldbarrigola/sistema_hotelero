<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Profesion extends Model
{
    protected $table="cli_profesion";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
