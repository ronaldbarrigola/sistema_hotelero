<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class TipoDoc extends Model
{
    protected $table="bas_tipo_doc";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'nombre',
        'abreviacion',
        'estado'
    ];

}
