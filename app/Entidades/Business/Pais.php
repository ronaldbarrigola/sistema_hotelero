<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table="cli_pais";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'dominio',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
