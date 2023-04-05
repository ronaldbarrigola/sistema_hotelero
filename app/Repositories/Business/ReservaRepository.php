<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Reserva;
use Carbon\Carbon;
use DB;

class ReservaRepository{

    public function obtenerReservas(){
        $reservas= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->leftjoin('res_servicio as serv','serv.id','=','r.servicio_id')
        ->leftjoin('pro_producto as prod','prod.id','=','serv.id')
        ->leftjoin('gob_tipo_habitacion as th','th.id','=','h.tipo_habitacion_id')
        ->leftjoin('res_paquete as q','q.id','=','r.paquete_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('r.id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'h.num_habitacion','th.descripcion as tipo_habitacion','q.descripcion as paquete','prod.descripcion as servicio',DB::raw('DATE_FORMAT(r.fecha_ini,"%d/%m/%Y") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%d/%m/%Y") as fecha_fin'),'r.num_adulto','r.num_nino','cp.descripcion as pais','cc.descripcion as ciudad','r.detalle','er.descripcion as estado_reserva')
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->orderBy('r.id','desc')
        ->get();

        return $reservas;
    }


    public function obtenerReservasDataTables(){
        $reservas=$this->obtenerReservas();
        return datatables()->of($reservas)->toJson();
    }

    public function obtenerReservasTimeLines(){

        //Sumar fechas
            // $endDate = $date->addYear();
            // $endDate = $date->addYears(5);
            // $endDate = $date->addMonth();
            // $endDate = $date->addMonths(5);
            // $endDate = $date->addDay();
            // $endDate = $date->addDay(5);
        //Restar fechas
            // $endDate = $date->subYear();
            // $endDate = $date->subYears(5);
            // $endDate = $date->subMonth();
            // $endDate = $date->subMonths(5);
            // $endDate = $date->subDay();
            // $endDate = $date->subDay(5);

        //$fecha_filtro=Carbon::now('America/La_Paz')->subMonth(6)->format('d/m/Y'); //filtro 6 meses atras
        $fecha_filtro=Carbon::now('America/La_Paz')->subMonth(6); //filtro 6 meses atras
        $reservas= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->select('r.id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y") as fecha'),'p.paterno',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'r.habitacion_id','h.num_habitacion',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y-%m-%d %H:%i:%s") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y-%m-%d %H:%i:%s") as fecha_fin'),'er.descripcion as estado_reserva','er.color')
        ->whereDate('r.fecha_ini','>=',$fecha_filtro)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->orderBy('r.id','desc')
        ->get();

        return $reservas;
    }

    public function obtenerReservaPorId($id){
       return Reserva::find($id);
    }

    public function insertarDesdeRequest(Request $request){

        $fecha_ini=$request->get('fecha_ini');
        $fecha_fin=$request->get('fecha_fin');

        $starDate = Carbon::parse($fecha_ini);
        $endDate = Carbon::parse($fecha_fin);

        $diferencia_en_dias=$starDate->diffInDays($endDate);

        if($diferencia_en_dias==0){
            $fecha_ini=$fecha_ini."T03:00:00"; //03:00 am
            $fecha_fin=$fecha_fin."T21:00:00"; //21:00 pm
        } else {
            $fecha_ini=$fecha_ini."T12:00:00";
            $fecha_fin=$fecha_fin."T12:00:00";
        }

        $reserva=new Reserva($request->all());
        $reserva->fecha_ini=$fecha_ini;
        $reserva->fecha_fin=$fecha_fin;
        $reserva->usuario_alta_id=Auth::user()->id;
        $reserva->usuario_modif_id=Auth::user()->id;
        $reserva->estado_reserva_id=0; //0: Reserva 1:Check In 2: Stand By 3: Check Out
        $reserva->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
        $reserva->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $reserva->estado=1;
        $reserva->save();

        return $reserva;
    }

    public function modificarDesdeRequest(Request $request){

        $fecha_ini=$request->get('fecha_ini');
        $fecha_fin=$request->get('fecha_fin');

        $starDate = Carbon::parse($fecha_ini);
        $endDate = Carbon::parse($fecha_fin);

        $diferencia_en_dias=$starDate->diffInDays($endDate);

        if($diferencia_en_dias==0){
            $fecha_ini=$fecha_ini."T03:00:00"; //03:00 am
            $fecha_fin=$fecha_fin."T21:00:00"; //21:00 pm
        } else {
            $fecha_ini=$fecha_ini."T12:00:00";
            $fecha_fin=$fecha_fin."T12:00:00";
        }

        $reserva=$this->obtenerReservaPorId($request->get('id'));
        if ($reserva!=null){
            $reserva->fill($request->all());
            $reserva->fecha_ini=$fecha_ini;
            $reserva->fecha_fin=$fecha_fin;
            $reserva->usuario_modif_id=Auth::user()->id;
            $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $reserva->update();
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
