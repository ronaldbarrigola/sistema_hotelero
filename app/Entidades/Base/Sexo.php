<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Sexo extends Model
{
    protected $table="bas_sexo";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'estado'
    ];

}
