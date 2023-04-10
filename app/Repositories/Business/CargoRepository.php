<?php

namespace App\Repositories\Business;
use Illuminate\Http\Request;
use App\Entidades\Business\Cargo;
use Carbon\Carbon;
use DB;

class CargoRepository{

    public function obtenerCargoPorId($id){
        return Cargo::find($id);
    }

    public function insertar($reserva_id,$transaccion_id){
        $cargo=new Cargo();
        $cargo->id=$reserva_id;
        $cargo->transaccion_id=$transaccion_id;
        $cargo->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $cargo->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $cargo->estado=1;
        $cargo->save();
        return $cargo;
    }

    public function modificar($reserva_id,$transaccion_id){
        $cargo=$this->obtenerCargoPorId($reserva_id);
        if(is_null($cargo)){
            $cargo->transaccion_id=$transaccion_id;
            $cargo->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $cargo->update();
        }
        return  $cargo;
    }

    public function eliminar($id){
        $cargo=$this->obtenerCargoPorId($id);
        if ( is_null($cargo) ){
           App::abort(404);
        }
        $cargo->estado='0';
        $cargo->update();
        return $cargo;
    }

}//fin clase
