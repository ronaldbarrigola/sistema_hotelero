<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    protected $table="gob_tipo_habitacion";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'codigo',
        'descripcion',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    public function delete() //Eliminacion logica
    {
        $this->estado = false;
        $this->save();
    }
}
