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
       ->join('bas_persona as p','p.id','=','h.cliente_id')
       ->join('bas_tipo_doc as d','d.id','=','p.tipo_doc_id')
       ->select('h.id',DB::raw('IFNULL(p.doc_id,"") as doc_id'),'d.nombre as tipo_documento','p.nombre','p.paterno','p.materno',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS huesped'),'h.fecha_ingreso','h.fecha_salida','h.estado_huesped_id','e.descripcion as estado_huesped')
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
        //Obtener array
        $vec_cliente_id=$request->get('vec_huesped_cliente_id');
        $vec_check_in=$request->get('vec_huesped_check_in');
        //Variable
        $reserva_id=$request->get('huesped_reserva_id');
        $huesped=null;
        $index=0;
        foreach ($vec_cliente_id as $cliente_id) {
            $check_in=($vec_check_in[$index]!=null)?$vec_check_in[$index]:0;
            $estado_huesped_id=0;
            $fecha_ingreso=null;
            if($check_in){
                $estado_huesped_id=1; //0:Pendiente 1: Check In 2: Check Out
                $fecha_ingreso=Carbon::now('America/La_Paz')->toDateTimeString();
            }
            $huesped=new Huesped();
            $huesped->estado_huesped_id=$estado_huesped_id; //0:Pendiente 1: Check In 2: Check Out
            $huesped->cliente_id=$cliente_id;
            $huesped->reserva_id=$reserva_id;
            $huesped->usuario_alta_id=Auth::user()->id;
            $huesped->usuario_modif_id=Auth::user()->id;
            $huesped->fecha_ingreso=$fecha_ingreso;
            $huesped->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $huesped->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $huesped->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $huesped->estado=1;
            $huesped->save();
            $index++;
        }
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

    public function estadoHuesped($id,$estado){
        $fecha=Carbon::now('America/La_Paz')->toDateTimeString();
        $huesped=$this->obtenerHuespedPorId($id);
        if ($estado==1) {
            $huesped->fecha_ingreso=$fecha;
        } else if ($estado==2) {
            $huesped->fecha_salida=$fecha;
        }
        $huesped->estado_huesped_id=$estado;
        $huesped->update();
        return $huesped;
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
