<?php

namespace App\Repositories\Base;
use Illuminate\Http\Request;
use App\Entidades\Base\Agencia;

use Illuminate\Http\JsonResponse;
use DB;


class AgenciaRepository{

    //=========================================================================================================================
    // OBTENER LISTA DE REGISTROS
    //=========================================================================================================================
    public function obtenerAgenciasPorSucursalDataTables($idSucursal){
        return datatables()->of(
            DB::table('bas_agencia as a')
            ->leftjoin('bas_sucursal as s','s.id','=','a.sucursal_id')
            ->select('a.id','s.nombre as sucursal','a.nombre','a.direccion','a.fono','a.observacion')
            ->where('a.sucursal_id','=',$idSucursal)
            ->where('a.estado','=','1')
            ->get()
        )->toJson();
    }

    public function obtenerListaAgencias(){
        $agencia=DB::table('bas_agencia as a')
                ->join('bas_sucursal as s','s.id','=','a.sucursal_id')
                ->select('a.id as agencia_id','s.nombre as sucursal','a.nombre as agencia','a.direccion','a.fono','a.observacion')
                ->where('a.estado','=','1')
                ->where('s.estado','=','1')
                ->get();
        return $agencia;
    }

    public function obtenerListaAgenciaConExclusion($agencia_id){
        $agencia=DB::table('bas_agencia as a')
                ->join('bas_sucursal as s','s.id','=','a.sucursal_id')
                ->select('a.id as agencia_id','s.nombre as sucursal','a.nombre as agencia','a.direccion','a.fono','a.observacion')
                ->where('a.id','!=',$agencia_id)
                ->where('a.estado','=','1')
                ->where('s.estado','=','1')
                ->get();
        return $agencia;
    }

     //===========================================================================================================
    //OBTENER agencias por idSucursal PARA COMBOBOX
    //===========================================================================================================
    public function obtenerAgenciasPorSucursal($idSucursal){
        $agencias= DB::table('bas_agencia as a')
                    ->select('a.id','a.nombre','a.direccion','a.fono','a.observacion')
                    ->where('a.sucursal_id','=',$idSucursal)
                    ->where('a.estado','=','1')
                    ->get();
        return $agencias;
    }

    public function obtenerAgenciaIdPorNombre($nombre){
        $agencias= DB::table('bas_agencia as a')
                    ->select('a.id','a.nombre','a.direccion','a.fono','a.observacion')
                    ->where('a.nombre','LIKE','%'.$nombre.'%')
                    ->where('a.estado','=','1')
                    ->first();
        return $agencias;
    }

     //=========================================================================================================================
    // OBTENER REGISTRO POR ID
    //=========================================================================================================================
    public function obtenerAgenciaPorId($idAgencia){
        $agencia=Agencia::find($idAgencia);
        return $agencia;
    }

    //=========================================================================================================================
    // INSERTAR
    //=========================================================================================================================
    public function insertarDesdeRequest(Request $request){
        try{
            $agencia=new Agencia($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            $agencia->estado=1;
            $agencia->save();
        }catch(\Exception $e){
            $errors=$e->getMessage();
            return new JsonResponse($errors, 500);
        }
        return $agencia;
    }

    //=========================================================================================================================
    // MODIFICAR
    //=========================================================================================================================
    public function modificarDesdeRequest(Request $request){
        try{
            $agencia=$this->obtenerAgenciaPorId($request->get('id'));
            $agencia->fill($request->all()); //llena datos desde el array entrante en el request.
            $agencia->update();
            return $agencia;
        }catch(\Exception $e){
            $errors=$e->getMessage();
            return new JsonResponde($errors,500);
        }
    }

    //=========================================================================================================================
    // ELIMINAR  POR ID
    //=========================================================================================================================
    public function eliminar($id){
        $agencia=$this->obtenerAgenciaPorId($id);
        if ( is_null($agencia) ){
            App::abort(404);
        }
        //$agencia->delete();
        $agencia->estado='0';
        $agencia->update();
        return $agencia;
    }

    //===========================================================================================================
}//fin clase
