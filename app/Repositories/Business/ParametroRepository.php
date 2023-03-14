<?php

namespace App\Repositories\Business;
use Illuminate\Http\Request;
use App\Entidades\Business\Parametro;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ParametroRepository{

     //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerParametroDataTables($grupo){
        return datatables()->of(
            DB::table('t_parametro as p')
            ->select('p.parametro_id','p.parametro','p.valor','p.grupo')
            ->where('p.grupo','=',$grupo)
            ->where('p.estado','=','1')
            ->orderBy('p.parametro_id','asc')
            ->get()
           )->toJson();
    }

    public function obtenerParametro($grupo){
        $parametro=DB::table('t_parametro as p')
        ->select('p.parametro_id','p.parametro','p.valor','p.grupo')
        ->where('p.grupo','=',$grupo)
        ->where('p.estado','=','1')
        ->orderBy('p.parametro_id','asc')
        ->get();
        return $parametro;
    }

    public function obtenerTipoCambio(){
        $parametro=DB::table('t_parametro as p')
        ->select('p.parametro as moneda','p.valor')
        ->where('p.grupo','=','TIPO_CAMBIO')
        ->where('p.estado','=','1')
        ->first();
        return $parametro;
    }

    public function obtenerGeneroIdPorDescripcion($descripcion){
        $parametro=DB::table('t_parametro as p')
        ->select('p.parametro_id','p.parametro','p.valor','p.grupo')
        ->where('p.grupo','=','GENERO')
        ->where('p.parametro','LIKE','%'.trim($descripcion).'%')
        ->where('p.estado','=','1')
        ->orderBy('p.parametro_id','asc')
        ->first();
        return $parametro;
    }

     //=========================================================================================================================
    // OBTENER OBJETO POR ID
    //=========================================================================================================================

    public function obtenerParametroPorId($id){
        return Parametro::find($id);
    }

     //=========================================================================================================================
    // INSERTAR
    //=========================================================================================================================
    public function insertarDesdeRequest(Request $request){
        $parametro=new Parametro($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
        $parametro->fecha_creacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $parametro->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $parametro->estado=1;
        $parametro->save();
        return $parametro;
    }

    //=========================================================================================================================
    // MODIFICAR OBJETO
    //=========================================================================================================================
    public function modificarDesdeRequest(Request $request){
        $parametro=$this->obtenerParametroPorId($request->get('parametro_id'));
        $parametro->fill($request->all()); //llena datos desde el array entrante en el request.
        $parametro->update();
        return $parametro;
    }

    //=========================================================================================================================
    // ELIMINAR OBJETO POR ID
    //=========================================================================================================================
    public function eliminar($id){
        $parametro=$this->obtenerParametroPorId($id);
        if ( is_null($parametro) ){
            App::abort(404);
        }
        $parametro->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $parametro->estado='0';
        $parametro->update();
        return $parametro;
    }
    //=========================================================================================================================
}
