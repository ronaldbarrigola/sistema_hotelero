<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class ProductoRepository{

    public function obtenerProductos(){
        $productos=DB::table('pro_producto as p')
        ->join('pro_categoria as c','c.id','=','p.categoria_id')
        ->select('p.id','p.descripcion as producto','c.descripcion as categoria')
        ->where('p.estado','=','1')
        ->where('c.estado','=','1')
        ->orderBy('p.id','desc')
        ->get();
        return $productos;
    }

    // public function obtenerServicios(){
    //     $productos=DB::table('pro_producto as p')
    //     ->join('pro_categoria as c','c.id','=','p.categoria_id')
    //     ->select('p.id','p.descripcion as producto','c.descripcion as categoria')
    //     ->where('c.descripcion','=','HABITACION') //servicio Habitacion
    //     ->where('p.estado','=','1')
    //     ->where('c.estado','=','1')
    //     ->orderBy('p.id','desc')
    //     ->get();
    //     return $productos;
    // }

    public function obtenerProductoDataTables(){
        $productos=$this->obtenerProductos();
        return datatables()->of($productos)->toJson();
    }

    public function obtenerProductoPorId($id){
        return Producto::find($id);
    }

    public function obtenerProductoPorDescripcion($p_descripcion){
        $producto=DB::table('pro_producto as p')
        ->select('p.id','p.descripcion')
        ->where(Str::lower('p.descripcion'),'=',Str::lower($p_descripcion))
        ->where('p.estado','=','1')
        ->first();
        return $producto;
    }

    public function insertarDesdeRequest(Request $request){
        $response="201"; //Created
        $p_descripcion=($request->get('descripcion')!=null)?$request->get('descripcion'):"";
        $producto=$this->obtenerProductoPorDescripcion($p_descripcion);
        if ( is_null($producto) ){
            $producto=new Producto($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            $producto->usuario_alta_id=Auth::user()->id;
            $producto->usuario_modif_id=Auth::user()->id;
            $producto->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $producto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $producto->estado=1;
            $producto->save();
        }  else {
            $response="202"; //Registro existente
        }

        return  $response;
    }

    public function modificarDesdeRequest(Request $request){
        $producto=$this->obtenerProductoPorId($request->get('id'));
        $producto->fill($request->all()); //llena datos desde el array entrante en el request.
        $producto->usuario_modif_id=Auth::user()->id;
        $producto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $producto->update();
        return $producto;
    }

    public function eliminar($id){
        $producto=$this->obtenerProductoPorId($id);
        if ( is_null($producto) ){
            App::abort(404);
        }
        $producto->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $producto->estado='0';
        $producto->update();
        return $producto;
    }

}
