<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Huesped;
use Carbon\Carbon;
use DB;

class HuespedRepository{

    public function obtenerHuespedes($reserva_id){
       $huespedes=DB::table('res_huesped as h')
       ->join('res_estado_huesped as e','e.id','=','h.estado_huesped_id')
       ->join('bas_persona as p','p.id','=','h.persona_id')
       ->join('bas_tipo_doc as d','d.id','=','p.tipo_doc_id')
       ->select('h.id',DB::raw('IFNULL(p.doc_id,"") as doc_id'),'d.nombre as tipo_documento','p.nombre','p.paterno','p.materno',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS huesped'),'h.fecha_ingreso','h.fecha_salida','e.descripcion as estado_huesped')
       ->where('h.reserva_id','=',$reserva_id)
       ->where('h.estado','=','1')
       ->orderBy('h.id','desc')
       ->get();
       return  $huespedes;
    }

    public function obtenerHuespedesDataTables($reserva_id){
        $huespedes=$this->obtenerHuespedes($reserva_id);
        return datatables()->of($huespedes)->toJson();
    }

    public function obtenerHuespedPorId($id){
        return Huesped::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $huesped=new Huesped($request->all());
        $huesped->usuario_alta_id=Auth::user()->id;
        $huesped->usuario_modif_id=Auth::user()->id;
        $huesped->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $huesped->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $huesped->estado=1;
        $huesped->save();

        return $huesped;
    }

    public function modificarDesdeRequest(Request $request){
        $huesped=$this->obtenerHuespedPorId($request->get('huesped_id'));
        $huesped->fill($request->all());
        $huesped->usuario_modif_id=Auth::user()->id;
        $huesped->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $huesped->update();
        return  $huesped;
    }

    public function eliminar($id){
        $huesped=$this->obtenerHuespedPorId($id);
        if ( is_null($huesped) ){
            App::abort(404);
        }
        $huesped->delete();
        return $huesped;
    }

}//fin clase
