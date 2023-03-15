<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Reserva;
use Carbon\Carbon;
use DB;

class ReservaRepository{

    public function obtenerReservasDataTables(){
        return datatables()->of(
            DB::table('res_reserva as r')
            ->join('bas_persona as p','p.id','=','r.cliente_id')
            ->join('res_habitacion as h','h.id','=','r.habitacion_id')
            ->leftjoin('res_paquete as q','q.id','=','r.paquete_id')
            ->leftjoin('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
            ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
            ->leftjoin('cli_ciudad as cc','cc.id','=','c.procedencia_ciudad_id')
            ->select('r.id','r.fecha',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'q.descripcion as paquete','er.descripcion as estado_reserva','r.fecha_ini','r.fecha_fin','r.detalle','r.num_adulto','r.nun_nino','h.codigo','cp.descripcion as pais','cc.descripcion as ciudad')
            ->where('r.estado','=','1')
            ->orderBy('r.id','desc')
            ->get()
        )->toJson();
    }

    public function obtenerReservaPorId($id){
       return Reserva::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $reserva=null;
        try{
            DB::beginTransaction();
                $request->request->add(['usuario_alta_id'=>Auth::user()->id]);
                $request->request->add(['usuario_modif_id'=>Auth::user()->id]);
                $reserva=new Reserva($request->all());
                $reserva->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $reserva->estado=1;
                $reserva->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $reserva;
    }

    public function modificarDesdeRequest(Request $request){
        $reserva=null;
        try{
            DB::beginTransaction();
            $request->request->add(['usuario_modif_id'=>Auth::user()->id]);
            $reserva=$this->obtenerReservaPorId($request->get('id'));
            if ( is_null($reserva) ){
                $reserva->fill($request->all());
                $reserva->usuario_modif_id=Auth::user()->id;
                $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $reserva->update();
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $reserva;
    }

    public function eliminar($id){

        $reserva=$this->obtenerReservaPorId($id);
        if ( is_null($reserva) ){
            App::abort(404);
        }
        $reserva->estado='0';
        $reserva->update();
        return $reserva;
    }

}//fin clase
