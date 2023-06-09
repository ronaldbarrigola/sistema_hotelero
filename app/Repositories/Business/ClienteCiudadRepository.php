<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\ClienteCiudad;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class ClienteCiudadRepository{

    public function obtenerCiudades(){
        $ciudades=DB::table('cli_pais as p')
        ->join('cli_ciudad as c','c.pais_id','=','p.id')
        ->select('c.id','c.descripcion as ciudad','p.descripcion as pais')
        ->where('p.estado','=','1')
        ->where('c.estado','=','1')
        ->orderBy('c.id','asc')
        ->get();
        return $ciudades;
    }

    public function obtenerCiudadesPorPaisId($pais_id){
        $ciudades=DB::table('cli_pais as p')
               ->join('cli_ciudad as c','c.pais_id','=','p.id')
               ->select('c.id','c.descripcion')
               ->where('c.pais_id','=',$pais_id)
               ->where('p.estado','=','1')
               ->where('c.estado','=','1')
               ->orderBy('c.descripcion','asc')
               ->get();
        return $ciudades;
    }

    public function obtenerCiudadesDataTables(){
        $ciudades=$this->obtenerCiudades();
        return datatables()->of($ciudades)->toJson();
    }

    public function obtenerCiudadPorId($id){
        return ClienteCiudad::find($id);
    }

     public function obtenerCiudadPorDescripcion($pais_id,$descripcion){
        $ciudad=DB::table('cli_ciudad as c')
        ->select('c.id','c.descripcion')
        ->where(Str::lower('c.descripcion'),'=',Str::lower($descripcion))
        ->where('c.pais_id','=',$pais_id)
        ->where('c.estado','=','1')
        ->first();
        return $ciudad;
     }

     public function insertarDesdeRequest(Request $request){
        $response="201"; //Created
        $descripcion=($request->get('descripcion')!=null)?$request->get('descripcion'):"";
        $pais_id=($request->get('pais_id')!=null)?$request->get('pais_id'):0;
        $ciudad=$this->obtenerCiudadPorDescripcion($pais_id,$descripcion);
        if ( is_null($ciudad) ){
            $ciudad=new ClienteCiudad($request->all());
            $ciudad->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $ciudad->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $ciudad->estado=1;
            $ciudad->save();
        }  else {
            $response="202"; //Registro existente
        }
        return response()->json(array ('response'=>$response,'ciudad'=>$ciudad));
     }

     public function modificarDesdeRequest(Request $request){
        $ciudad=$this->obtenerCiudadPorId($request->get('id'));
        $ciudad->fill($request->all());
        $ciudad->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $ciudad->update();
         return  $ciudad;
     }

     public function eliminar($id){
         $ciudad=$this->obtenerCiudadPorId($id);
         $ciudad->estado='0';
         $ciudad->update();
         return $ciudad;
     }

}
