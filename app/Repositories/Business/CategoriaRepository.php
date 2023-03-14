<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Categoria;
use Carbon\Carbon;
use DB;

class CategoriaRepository{
    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerCategoriaDataTables(){
        return datatables()->of(
            DB::table('pro_categoria as c')
           ->select('c.id','c.descripcion')
           ->where('c.estado','=','1')
           ->orderBy('c.id','asc')
           ->get()
           )->toJson();
    }

    public function obtenerCategoria(){
        $categoria=DB::table('pro_categoria as c')
        ->select('c.id','descripcion')
        ->where('c.estado','=','1')
        ->orderBy('c.id','asc')
        ->get();
        return $categoria;
    }

    public function obtenerCategoriaPorId($id){
        return Categoria::find($id);
    }

    //=========================================================================================================================
    // INSERTAR
    //=========================================================================================================================
    public function insertarDesdeRequest(Request $request){
        $categoria=new Categoria($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
        $categoria->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $categoria->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $categoria->estado=1;
        $categoria->save();
        return $categoria;
    }

    //=========================================================================================================================
    // MODIFICAR OBJETO
    //=========================================================================================================================
    public function modificarDesdeRequest(Request $request){
        $categoria=$this->obtenerCategoriaPorId($request->get('id'));
        $categoria->fill($request->all()); //llena datos desde el array entrante en el request.
        $categoria->update();
        return $categoria;
    }

    //=========================================================================================================================
    // ELIMINAR OBJETO POR ID
    //=========================================================================================================================
    public function eliminar($id){
        $categoria=$this->obtenerCategoriaPorId($id);
        if ( is_null($categoria) ){
            App::abort(404);
        }
        $categoria->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $categoria->estado='0';
        $categoria->update();
        return $categoria;
    }
    //=========================================================================================================================
}
