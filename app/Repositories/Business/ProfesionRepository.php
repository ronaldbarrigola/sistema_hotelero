<?php

namespace App\Repositories\Business;

use Carbon\Carbon;
use App\Entidades\Business\Profesion;
use DB;

class ProfesionRepository{

    public function obtenerProfesiones(){
       $profesiones=DB::table('cli_profesion as p')
              ->select('p.id','p.descripcion')
              ->where('p.estado','=','1')
              ->orderBy('p.descripcion','asc')
              ->get();
       return $profesiones;
    }

    public function obtenerProfesionesDataTables(){
        $profesiones=$this->obtenerProfesiones();
        return datatables()->of($profesiones)->toJson();
    }

    public function obtenerProfesionPorId($id){
        return Profesion::find($id);
    }

    public function obtenerProfesionPorDescripcion($descripcion){
        $profesion=DB::table('cli_profesion as p')
        ->select('p.id','p.descripcion')
        ->where(Str::lower('p.descripcion'),'=',Str::lower($descripcion))
        ->where('p.estado','=','1')
        ->first();
        return $profesion;
    }

    public function insertarDesdeRequest(Request $request){
        $response="201"; //Created
        $descripcion=($request->get('descripcion')!=null)?$request->get('descripcion'):"";
        $profesion=$this->obtenerProfesionPorDescripcion($descripcion);
        if ( is_null($profesion) ){
            $profesion=new Profesion($request->all());
            $profesion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $profesion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $profesion->estado=1;
            $profesion->save();
        }  else {
            $response="202"; //Registro existente
        }
        return response()->json(array ('response'=>$response,'profesion'=>$profesion));
    }

     public function modificarDesdeRequest(Request $request){
        $profesion=$this->obtenerProfesionPorId($request->get('id'));
        $profesion->fill($request->all());
        $profesion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $profesion->update();
         return  $profesion;
     }

     public function eliminar($id){
         $profesion=$this->obtenerProfesionPorId($id);
         $profesion->estado='0';
         $profesion->update();
         return $profesion;
     }

}//fin clase
