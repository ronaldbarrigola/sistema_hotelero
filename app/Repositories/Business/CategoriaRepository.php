<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Categoria;
use Carbon\Carbon;
use DB;

class CategoriaRepository{

    public function obtenerCategorias(){
        $categorias=DB::table('pro_categoria as c')
        ->select('c.id','descripcion')
        ->where('c.estado','=','1')
        ->orderBy('c.id','asc')
        ->get();
        return $categorias;
    }

    public function obtenerCategoriaDataTables(){
        $categorias=$this->obtenerCategorias();
        return datatables()->of($categorias)->toJson();
    }

    public function obtenerCategoriaPorId($id){
        return Categoria::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $categoria=new Categoria($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
        $categoria->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $categoria->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $categoria->estado=1;
        $categoria->save();
        return $categoria;
    }

    public function modificarDesdeRequest(Request $request){
        $categoria=$this->obtenerCategoriaPorId($request->get('id'));
        $categoria->fill($request->all()); //llena datos desde el array entrante en el request.
        $categoria->update();
        return $categoria;
    }

    public function eliminar($id){
        $categoria=$this->obtenerCategoriaPorId($id);
        $categoria->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $categoria->estado='0';
        $categoria->update();
        return $categoria;
    }
}
