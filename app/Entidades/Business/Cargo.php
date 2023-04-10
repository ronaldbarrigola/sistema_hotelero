<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table="res_cargo";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'transaccion_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
