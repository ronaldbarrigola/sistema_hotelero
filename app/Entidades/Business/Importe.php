<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Importe extends Model
{
    protected $table="con_importe";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'pago_id',
        'forma_pago_id',
        'concepto',
        'monto',
        'detalle',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    public function delete() //Eliminacion logica
    {
       $this->estado = false;
       $this->save();
    }

    //Relacion uno a muchos (Inversa)
    public function pago()
    {
       return $this->belongsTo(Pago::class,'pago_id','id');
    }
}
