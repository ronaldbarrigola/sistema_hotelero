<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\TransaccionDetalle;
use Carbon\Carbon;
use DB;

class TransaccionDetalleRepository{

    public function obtenerTransaccionDetallePorId($id){
        return TransaccionDetalle::find($id);
    }

    public function insertarDesdeRequest(Request $request){

        $transaccion_id=($request['transaccion_id']!=null)?$request['transaccion_id']:0;
        $fecha_ini = $request->get('fecha_ini');
        $cantidad=$request['reserva_cantidad'];
        $precio_unidad=$request['reserva_precio_unidad'];


        //Validaciones
        $cantidad=($cantidad!=null)?$cantidad:0;
        $precio_unidad=($precio_unidad!=null)?$precio_unidad:0;

        $fecha_inicial = Carbon::parse($fecha_ini);
        $fecha_final = Carbon::parse($fecha_ini)->addDay();

        for ($i = 1; $i <= $cantidad; $i++) {
            $transaccion_detalle=new TransaccionDetalle();
            $transaccion_detalle->transaccion_id=$transaccion_id;
            $transaccion_detalle->cantidad=1;
            $transaccion_detalle->precio_unidad=round($precio_unidad,2);
            $transaccion_detalle->monto=round($precio_unidad,2);
            $transaccion_detalle->usuario_alta_id=Auth::user()->id;
            $transaccion_detalle->usuario_modif_id=Auth::user()->id;
            $transaccion_detalle->fecha_ini= $fecha_inicial->toDateTimeString();
            $transaccion_detalle->fecha_fin= $fecha_final->toDateTimeString();
            $transaccion_detalle->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion_detalle->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion_detalle->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion_detalle->estado=1;
            $transaccion_detalle->save();
            $fecha_inicial = $fecha_inicial->addDay();
            $fecha_final = $fecha_final->addDay();

        }
    }

    public function modificarDesdeRequest(Request $request){
       //Por implementar
    }

    public function eliminar($id){
        $transaccion_detalle=$this->obtenerTransaccionDetallePorId($id);
        if ( is_null($transaccion_detalle) ){
            App::abort(404);
        }
        $transaccion_detalle->estado='0';
        $transaccion_detalle->update();
        return $transaccion_detalle;
    }

}//fin clase
