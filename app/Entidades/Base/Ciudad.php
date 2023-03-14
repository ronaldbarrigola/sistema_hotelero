<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
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
