<?php

namespace App\Entidades\Business;


use Illuminate\Database\Eloquent\Model;



class Reserva extends Model
{

    protected $table="res_reserva";
    protected $primaryKey="id";
    public $timestamps=false;



    protected $fillable=[
        'fecha',
        'cliente_id',
        'estado_reserva_id',
        'paquete_id',
        'habitacion_id',
        'procedencia_ciudad_id',
        'procedencia_pais_id',
        'procedencia_ciudad_id',
        'grupo_id',
        'servicio_id',
        'motivo_id',
        'usuario_alta_id',
        'usuario_modif_id',
        'fecha_ini',
        'fecha_fin',
        'hora_ini',
        'hora_fin',
        'detalle',
        'num_adulto',
        'num_nino',
        'estado',
        'fecha_creacion',
        'fecha_modificacion'
    ];

    public function delete() //Eliminacion logica
    {
        $this->cargos()->each(function ($transaccion) {//Eliminacion logica en cascada
            $transaccion->delete();
        });

        $this->transacciones()->each(function ($transaccion) {//Eliminacion logica en cascada
            $transaccion->delete();
        });

        $this->estado = false;
        $this->save();
    }

    //Relacion 1 a muchos (Inversa)
    public function cliente()
    {
       return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    //Relacion 1 a muchos (Inversa)
    public function servicio()
    {
        return $this->belongsTo(Servicio::class,'servicio_id','id');
    }

    //Relacion 1 a muchos (Inversa)
    public function habitacion()
    {
       return $this->belongsTo(Habitacion::class,'habitacion_id','id');
    }

    //Relacion 1 a muchos (Inversa)
    public function estadoReserva()
    {
       return $this->belongsTo(EstadoReserva::class,'estado_reserva_id','id');
    }

    //Relacion 1 a muchos
    public function cargos()
    {
       return $this->hasMany(Cargo::class,'reserva_id','id');
    }

    //Relacion 1 a muchos
    public function transacciones()
    {
       return $this->hasMany(Transaccion::class,'reserva_id','id');
    }

     //Relacion 1 a muchos
     public function huespedes()
     {
        return $this->hasMany(Huesped::class,'reserva_id','id');
     }

}
