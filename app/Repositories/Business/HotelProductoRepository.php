<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\HotelProducto;
use App\Entidades\Business\Producto;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class HotelProductoRepository{

    public function obtenerHotelProductos(){
        $hotelProductos=DB::table('pro_hotel_producto as h')
        ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
        ->select('h.id','p.descripcion as producto','h.precio','h.activado')
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('h.estado','=','1')
        ->orderBy('h.id','desc')
        ->get();
        return $hotelProductos;
    }

    public function obtenerHotelProductoPorId($id){
        return HotelProducto::find($id);
    }

    public function obtenerProductoPorDescripcion($descripcion){
        $producto=Producto::where("descripcion",$descripcion)->where("estado",1)->first();
        return $producto->hotelProductos->where("agencia_id",Auth::user()->agencia_id)->where("estado",1)->first();
    }

    public function insertarDesdeRequest(Request $request){
        $hotelProducto=new HotelProducto($request->all());
        $hotelProducto->agencia_id=Auth::user()->agencia_id;
        $hotelProducto->usuario_alta_id=Auth::user()->id;
        $hotelProducto->usuario_modif_id=Auth::user()->id;
        $hotelProducto->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $hotelProducto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $hotelProducto->estado=1;
        $hotelProducto->save();
        return $hotelProducto;
    }

    public function modificarDesdeRequest(Request $request){
        $hotelProducto=$this->obtenerHotelProductoPorId($request->get('id'));
        $hotelProducto->fill($request->all()); //llena datos desde el array entrante en el request.
        $hotelProducto->usuario_modif_id=Auth::user()->id;
        $hotelProducto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $hotelProducto->update();
        return $hotelProducto;
    }

    public function eliminar($id){
        $hotelProducto=$this->obtenerHotelProductoPorId($id);
        if ( is_null($hotelProducto) ){
            App::abort(404);
        }
        $hotelProducto->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $hotelProducto->estado='0';
        $hotelProducto->update();
        return $hotelProducto;
    }

}
