<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table="bas_empresa";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'logo',
        'estado'
    ];

    public static function nombreEmpresa(){
        return (Empresa::findOrFail(1)->nombre);
    }

    public static function logoEmpresa(){
        return (Empresa::findOrFail(1)->logo);
    }
}
