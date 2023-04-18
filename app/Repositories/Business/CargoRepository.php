<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\Cargo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class CargoRepository{

    public function obtenerCargoPorId($id){
        return Cargo::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $reserva_id=($request['foreign_reserva_id']!=null)?$request['foreign_reserva_id']:0;
        $cargo=new Cargo($request->all());
        $cargo->reserva_id=$reserva_id;
        $cargo->usuario_alta_id=Auth::user()->id;
        $cargo->usuario_modif_id=Auth::user()->id;
        $cargo->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
        $cargo->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $cargo->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $cargo->estado=1;
        $cargo->save();
        return $cargo;
    }

    public function modificarDesdeRequest(Request $request){
        $cargo=$this->obtenerCargoPorId($request->get('cargo_id'));
        $cargo->fill($request->all()); //llena datos desde el array entrante en el request.
        $cargo->usuario_modif_id=Auth::user()->id;
        $cargo->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $cargo->update();
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
