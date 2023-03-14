<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $table='t_parametro';
    protected $primaryKey="parametro_id";
    public $timestamps=false;

    protected $fillable=['parametro',
                         'valor',
                         'grupo',                         
                         'estado',
                         'fecha_creacion',
                         'fecha_modificacion'
                        ];
}
