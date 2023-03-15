<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Producto;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ProductoRepository{

    public function obtenerProductos(){
        $productos=DB::table('pro_producto as p')
        ->leftjoin('pro_categoria as c','c.id','=','p.categoria_id')
        ->select('p.id','p.descripcion as producto','p.precio','c.descripcion as categoria')
        ->where('p.estado','=','1')
        ->orderBy('p.id','desc')
        ->get();
        return $productos;
    }

    public function obtenerProductoDataTables(){
        $productos=$this->obtenerProductos();
        return datatables()->of($productos)->toJson();
    }

    public function obtenerProductoPorId($id){
        return Producto::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $producto=new Producto($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
        $producto->usuario_alta_id=Auth::user()->id;
        $producto->usuario_modif_id=Auth::user()->id;
        $producto->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $producto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $producto->estado=1;
        $producto->save();
        return $producto;
    }

    //=========================================================================================================================
    // MODIFICAR OBJETO
    //=========================================================================================================================
    public function modificarDesdeRequest(Request $request){
        $producto=$this->obtenerProductoPorId($request->get('id'));
        $producto->usuario_modif_id=Auth::user()->id;
        $producto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $producto->fill($request->all()); //llena datos desde el array entrante en el request.
        $producto->update();
        return $producto;
    }

    //=========================================================================================================================
    // ELIMINAR OBJETO POR ID
    //=========================================================================================================================
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
    //=========================================================================================================================
}
