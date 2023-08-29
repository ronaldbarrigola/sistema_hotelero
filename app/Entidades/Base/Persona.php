<?php

namespace App\Entidades\Base;

use Illuminate\Database\Eloquent\Model;
use DB;

class Persona extends Model
{
    protected $table="bas_persona";
    protected $primaryKey="id";
    public $timestamps=true;

    protected $fillable = [
        'nombre','paterno','materno',
        'sexo_id','fecha_nac','tipo_doc_id','doc_id','ciudad_exp_id','estado_civil_id',
        'tipo_persona_id','email','telefono','direccion',
        'usuario_alta_id','estado'
    ];

    //Relacion 1 a 1 Con usuario
    public function usuario()
    {
        return $this->hasOne('App\Models\User','id');//'id' es la columna de relacion en bas_usuario
    }

    //Relacion 1 a 1 Con cliente
    public function cliente()
    {
        return $this->hasOne('App\Entidades\Business\Cliente','id');
    }

    //Relacion 1 a 1 Con vendedor
    public function vendedor()
    {
        return $this->hasOne('App\Entidades\Smart\Vendedor','id');//'id' es la columna de relacion en vnt_vendedor
    }

    public function nombre_completo()
    {
        $nombre=($this->nombre!=null)?$this->nombre:"";
        $paterno=($this->paterno!=null)?$this->paterno:"";
        $materno=($this->materno!=null)?$this->materno:"";
        $nombre_completo=$nombre." ".$paterno." ". $materno;
        return $nombre_completo;
    }

}
