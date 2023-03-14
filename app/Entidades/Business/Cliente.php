<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table="cli_cliente";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'pais_id',
        'ciudad_id',
        'profesion_id',
        'empresa_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'detalle',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];
}
