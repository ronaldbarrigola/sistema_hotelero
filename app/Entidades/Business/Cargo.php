<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table="con_cargo";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'reserva_id',
        'detalle',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    public function delete() //Eliminacion logica
    {
        $this->transacciones()->each(function ($transaccion) {//Eliminacion logica en cascada
            $transaccion->delete();
        });

        $this->estado = false;
        $this->save();
    }

    //Relacion 1 a muchos
    public function transacciones()
    {
       return $this->hasMany(Transaccion::class,'cargo_id','id');
    }

    //Relacion uno a muchos (Inversa)
    public function reserva()
    {
        return $this->belongsTo(Reserva::class,'reserva_id','id');
    }


}
