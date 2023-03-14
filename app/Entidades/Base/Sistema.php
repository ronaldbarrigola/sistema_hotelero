<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    Protected $table="bas_sistema";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'version',
        'tipo'
    ];

    public static function nombreVersion(){
        $sistema=Sistema::findOrFail(1);
        return ($sistema->nombre.' - V.'.$sistema->version);
    }
}
