<?php

namespace App\Repositories\Base;
use Illuminate\Http\Request;
use App\Entidades\Base\Rol;

use Illuminate\Http\JsonResponse;
use DB;


class RolRepository{


    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerRolesDataTables(){
        return datatables()->of(
            DB::table('bas_rol as r')
            ->select('r.id','r.codigo','r.nombre','r.descripcion')
            ->where('r.estado','=','1')
            ->orderBy('r.id','desc')
            ->get()
        )->toJson();
    }

     //=========================================================================================================================
    // OBTENER ROL POR ID
    //=========================================================================================================================
    public function obtenerRolPorId($idRol){
        $rol=Rol::find($idRol);
        return $rol;
    }

     //=========================================================================================================================
    // OBTENER ROL POR ID
    //=========================================================================================================================
    public function obtenerRolPorCodigoEliminado($codRol){
        $rol = DB::table('bas_rol as r')
        ->select('*')
        ->where('r.codigo', $codRol)
        ->where('r.estado', 0)
        ->first();
        return $rol;
    }

    //=========================================================================================================================
    // INSERTAR
    //=========================================================================================================================
    public function insertarDesdeRequest(Request $request){
        try{
            $rol=new Rol($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            $rol->estado=1;
            $rol->save();
        }catch(\Exception $e){
            $errors=$e->getMessage();
            return new JsonResponse($errors, 500);
        }
        return $rol;
    }

    //=========================================================================================================================
    // MODIFICAR OBJETO
    //=========================================================================================================================
    public function modificarDesdeRequest(Request $request){
        try{
            $rol=$this->obtenerRolPorId($request->get('id'));
            $rol->fill($request->all()); //llena datos desde el array entrante en el request.
            $rol->update();
            return $rol;
        }catch(\Exception $e){
            $errors=$e->getMessage();
            return new JsonResponde($errors,500);
        }
    }

    //=========================================================================================================================
    // ELIMINAR OBJETO POR ID
    //=========================================================================================================================
    public function eliminar($id){
        $rol=$this->obtenerRolPorId($id);
        if ( is_null($rol) ){
            App::abort(404);
        }
        //$rol->delete();
        $rol->estado='0';
        $rol->update();
        return $rol;
    }
}
