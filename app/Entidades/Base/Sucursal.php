<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table="bas_sucursal";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'ciudad_id',
        'observacion',
        'estado'
    ];

}
