<?php

namespace App\Repositories\Base;
use Illuminate\Http\Request;
use App\Entidades\Base\Sucursal;

use Illuminate\Http\JsonResponse;
use DB;


class SucursalRepository{


    //=========================================================================================================================
    // OBTENER LISTA DE REGISTROS
    //=========================================================================================================================
    public function obtenerSucursalesDataTables(){
        return datatables()->of(
            DB::table('bas_sucursal as s')
            ->leftjoin('bas_ciudad as c','c.id','=','s.ciudad_id')
            ->select('s.id','s.nombre','c.nombre as ciudad','s.observacion')
            ->where('s.estado','=','1')
            ->get()
        )->toJson();
    }

    //=========================================================================================================================
    // LISTA DE SUCURSALES PARA COMBOBOX EN VISTA AGENCIAS.
    //=========================================================================================================================
    public function obtenerSucursales(){
        $sucursales=DB::table('bas_sucursal')
        ->where('estado','=','1')
        ->orderBy('id','asc')
        ->get();
        return $sucursales;
    }

     //=========================================================================================================================
    // OBTENER REGISTRO POR ID
    //=========================================================================================================================
    public function obtenerSucursalPorId($idSucursal){
        $sucursal=Sucursal::find($idSucursal);
        return $sucursal;
    }

    //=========================================================================================================================
    // INSERTAR
    //=========================================================================================================================
    public function insertarDesdeRequest(Request $request){
        try{
            $sucursal=new Sucursal($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            $sucursal->estado=1;
            $sucursal->save();
        }catch(\Exception $e){
            $errors=$e->getMessage();
            return new JsonResponse($errors, 500);
        }
        return $sucursal;
    }

    //=========================================================================================================================
    // MODIFICAR
    //=========================================================================================================================
    public function modificarDesdeRequest(Request $request){
        try{
            $sucursal=$this->obtenerSucursalPorId($request->get('id'));
            $sucursal->fill($request->all()); //llena datos desde el array entrante en el request.
            $sucursal->update();
            return $sucursal;
        }catch(\Exception $e){
            $errors=$e->getMessage();
            return new JsonResponde($errors,500);
        }
    }

    //=========================================================================================================================
    // ELIMINAR  POR ID
    //=========================================================================================================================
    public function eliminar($id){
        $sucursal=$this->obtenerSucursalPorId($id);
        if ( is_null($sucursal) ){
            App::abort(404);
        }
        //$sucursal->delete();
        $sucursal->estado='0';
        $sucursal->update();
        return $sucursal;
    }
}
