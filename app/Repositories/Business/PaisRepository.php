<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Pais;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class PaisRepository{

    public function obtenerPaises(){
       $paises=DB::table('cli_pais as p')
              ->select('p.id','p.descripcion','p.dominio')
              ->where('p.estado','=','1')
              ->orderBy('p.id','desc')
              ->get();
       return $paises;
    }

    public function obtenerPaisesDataTables(){
        $paises=$this->obtenerPaises();
        return datatables()->of($paises)->toJson();
    }

    public function obtenerPaisPorId($id){
        return Pais::find($id);
     }

     public function insertarDesdeRequest(Request $request){
        $pais=new Pais($request->all());
        $pais->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pais->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pais->estado=1;
        $pais->save();
         return $pais;
     }

     public function modificarDesdeRequest(Request $request){
        $pais=$this->obtenerPaisPorId($request->get('id'));
        $pais->fill($request->all());
        $pais->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pais->update();
         return  $pais;
     }

     public function eliminar($id){
         $pais=$this->obtenerPaisPorId($id);
         if ( is_null($pais) ){
             App::abort(404);
         }
         $pais->estado='0';
         $pais->update();
         return $pais;
     }

}//fin clase
