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

    //Relacion 1 a muchos
    public function persona()
    {
       return $this->hasMany(Persona::class,'tipo_doc_id','id');
    }

}
