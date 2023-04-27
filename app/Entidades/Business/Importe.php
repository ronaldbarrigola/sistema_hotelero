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
        'referencia',
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

    //Relacion 1 a muchos
    public function transaccionPago()
    {
       return $this->hasMany(TransaccionPago::class,'pago_id','id');
    }
}
