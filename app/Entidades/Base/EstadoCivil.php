<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    protected $table="bas_estado_civil";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'estado'
    ];

}
