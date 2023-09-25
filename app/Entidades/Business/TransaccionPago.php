<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class TransaccionPago extends Model
{
    protected $table="con_transaccion_pago";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'pago_id',
        'transaccion_id',
        'tipo_transaccion_id',
        'detalle',
        'monto',
        'usuario_alta_id',
        'usuario_modif_id',
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
    public function transaccion()
    {
      return $this->belongsTo(Transaccion::class,'transaccion_id','id');
    }

    //Relacion uno a muchos (Inversa)
    public function pago()
    {
       return $this->belongsTo(Pago::class,'pago_id','id');
    }

}
