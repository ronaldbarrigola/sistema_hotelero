<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class TransaccionDetalle extends Model
{
    protected $table="con_transaccion_detalle";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'transaccion_id',
        'fecha',
        'fecha_ini',
        'fecha_fin',
        'cantidad',
        'precio_unidad',
        'monto',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    //Relacion uno a muchos (Inversa)
    public function transaccion()
     {
        return $this->belongsTo(Transaccion::class,'transaccion_id','id');
    }
}
