<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\Cargo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class CargoRepository{

    public function obtenerCargoPorReservaId($reserva_id){//Cuenta pasajero
        $cargos=DB::table('con_cargo as c')
        ->leftjoin('con_transaccion as tr','tr.cargo_id','=','c.id')
        ->leftjoin('pro_hotel_producto as h','h.id','=','tr.hotel_producto_id')
        ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
        ->select('c.id as cargo_id','tr.id',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),'p.descripcion as producto','tr.detalle','tr.cantidad','tr.precio_unidad',DB::raw('tr.cantidad*tr.precio_unidad as total'),'tr.descuento_porcentaje','tr.descuento','tr.monto as cargo',DB::raw('(SELECT IFNULL(sum(tp.monto),0) FROM con_transaccion_pago tp WHERE tp.transaccion_id = tr.id AND tp.estado=1) as pago'))
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('c.reserva_id','=',$reserva_id)
        ->where('tr.estado','=','1')
        ->orderBy('tr.fecha','desc')
        ->orderBy('tr.id','desc')
        ->get();

        //Calcular Saldo
        if($cargos!=null){
            $saldo=0;
            foreach($cargos as $row){
                $saldo=$row->cargo - $row->pago;
                $row->saldo=round($saldo,2);
            }
        }

        return  $cargos;
    }

    public function obtenerCargoPorReservaIdDataTables($reserva_id){
        $cargos=$this->obtenerCargoPorReservaId($reserva_id);
        return datatables()->of($cargos)->toJson();
    }

    public function obtenerCargoPorId($id){
        return Cargo::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        //validaciones
        $reserva_id=($request['reserva_id']!=null)?$request['reserva_id']:0;

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
