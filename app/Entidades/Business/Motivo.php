<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    protected $table="res_motivo";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
