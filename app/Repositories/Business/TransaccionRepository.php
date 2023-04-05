<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Transaccion;
use Carbon\Carbon;
use DB;

class TransaccionRepository{

    public function obtenerTransacciones(){
       $transacciones=DB::table('con_transaccion as tr')
       ->leftjoin('pro_hotel_producto as h','h.id','=','tr.hotel_producto_id')
       ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
       ->select('tr.id',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),'p.descripcion','tr.detalle','tr.cantidad','tr.precio_unidad','tr.descuento_porcentaje','tr.descuento',DB::raw('(CASE WHEN tr.factor=-1 THEN IFNULL(tr.monto,0)  ELSE 0 END) AS cargo'),DB::raw('(CASE WHEN tr.factor=1  THEN IFNULL(tr.monto,0)  ELSE 0 END) AS pago'))
       ->where('h.agencia_id','=',Auth::user()->agencia_id)
       ->where('tr.estado','=','1')
       ->orderBy('tr.id','desc')
       ->get();
       return  $transacciones;
    }

    public function obtenerTransaccionDataTables(){
        $transacciones=$this->obtenerTransacciones();
        return datatables()->of($transacciones)->toJson();
    }

    public function obtenerTransaccionPorId($id){
        return Transaccion::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $transaccion=null;
        try{
            DB::beginTransaction();

            $factor=($request->get('tipo_transaccion_id')=="P")?1:-1;  //-1:Cargo 1:Pago donde P es PAGO
            $transaccion=new Transaccion($request->all());
            $transaccion->factor=$factor; //-1:Cargo 1:Pago
            $transaccion->usuario_alta_id=Auth::user()->id;
            $transaccion->usuario_modif_id=Auth::user()->id;
            $transaccion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->estado=1;
            $transaccion->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $transaccion;
    }

    public function modificarDesdeRequest(Request $request){
        $transaccion=null;
        try{
            DB::beginTransaction();

            $transaccion->fill($request->all()); //llena datos desde el array entrante en el request.
            $transaccion->usuario_modif_id=Auth::user()->id;
            $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->update();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $transaccion;
    }

    public function eliminar($id){
        $transaccion=$this->obtenerTransaccionPorId($id);
        if ( is_null($transaccion) ){
            App::abort(404);
        }
        $transaccion->estado='0';
        $transaccion->update();
        return $transaccion;
    }

}//fin clase
