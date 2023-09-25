<?php

namespace App\Entidades\Business;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table="con_pago";
    protected $primaryKey="id";
    public $timestamps=false;

    protected $fillable=[
        'fecha',
        'forma_pago_id',
        'cliente_id',
        'nit',
        'nombre',
        'email',
        'celular',
        'detalle',
        'agencia_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    public function delete() //Eliminacion logica
    {
        $this->transaccionPago()->each(function ($row) {//Eliminacion logica en cascada
            $row->delete();
        });

        $this->importe()->each(function ($row) {//Eliminacion logica en cascada
            $row->delete();
        });

        $this->estado = false;
        $this->save();
    }

    //Relacion 1 a muchos
    public function transaccionPago()
    {
       return $this->hasMany(TransaccionPago::class,'pago_id','id');
    }

    //Relacion 1 a muchos
    public function importe()
    {
       return $this->hasMany(Importe::class,'pago_id','id');
    }
}
