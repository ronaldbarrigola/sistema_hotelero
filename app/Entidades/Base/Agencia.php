<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $table="bas_agencia";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'sucursal_id',
        'nombre',
        'direccion',
        'fono',
        'tipo_id',
        'observacion',
        'estado'
    ];

}
