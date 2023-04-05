<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Venta;
use Carbon\Carbon;
use DB;

class VentaRepository{

    public function obtenerVentas(){
       $ventas=DB::table('con_venta as v')
       ->select('v.id',DB::raw('DATE_FORMAT(v.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),'v.nombre','v.detalle')
       ->where('v.estado','=','1')
       ->orderBy('v.id','desc')
       ->get();
       return  $ventas;
    }

    public function obtenerVentasDataTables(){
        $ventas=$this->obtenerVentas();
        return datatables()->of($ventas)->toJson();
    }

    public function obtenerVentaPorId($id){
        return Venta::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $venta=null;
        try{
            DB::beginTransaction();

            $venta=new Venta($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            $venta->usuario_alta_id=Auth::user()->id;
            $venta->usuario_modif_id=Auth::user()->id;
            $venta->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $venta->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $venta->estado=1;
            $venta->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $venta;
    }

    public function modificarDesdeRequest(Request $request){
        $venta=null;
        try{
            DB::beginTransaction();

            $venta->fill($request->all()); //llena datos desde el array entrante en el request.
            $venta->usuario_modif_id=Auth::user()->id;
            $venta->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $venta->update();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $venta;
    }

    public function eliminar($id){
        $venta=$this->obtenerVentaPorId($id);
        if ( is_null($venta) ){
            App::abort(404);
        }
        $venta->estado='0';
        $venta->update();
        return $venta;
    }

}//fin clase
