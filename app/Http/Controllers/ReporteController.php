<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Business\ReporteRepository;
use App\Repositories\Business\HabitacionRepository;
use App\Repositories\Business\ClienteRepository;
use App\Repositories\Business\EstadoReservaRepository;
use App\Repositories\Business\EstadoHuespedRepository;
use App\Repositories\Business\TipoHabitacionRepository;

class ReporteController extends Controller
{
    protected $reporteRep;
    protected $habitacionRep;
    protected $clienteRep;
    protected $estadoReservaRep;
    protected $estadoHuespedRep;
    protected $tipoHabitacionRep;

    public function __construct(ReporteRepository $reporteRep,HabitacionRepository $habitacionRep,ClienteRepository $clienteRep,EstadoReservaRepository $estadoReservaRep,EstadoHuespedRepository $estadoHuespedRep,TipoHabitacionRepository $tipoHabitacionRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->reporteRep=$reporteRep;
        $this->habitacionRep=$habitacionRep;
        $this->clienteRep=$clienteRep;
        $this->estadoReservaRep=$estadoReservaRep;
        $this->estadoHuespedRep=$estadoHuespedRep;
        $this->tipoHabitacionRep=$tipoHabitacionRep;
    }

    public function obtenerReservas(Request $request){
        if($request->ajax()){
            $habitacion_id=$request->get("habitacion_id");
            $cliente_id=$request->get("cliente_id");
            $estado_reserva_id=$request->get("estado_reserva_id");
            $tipo_habitacion_id=$request->get("tipo_habitacion_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
            $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
            return $this->reporteRep->obtenerReservasDataTables($habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin);
        }else{
            $habitaciones=$this->habitacionRep->obtenerHabitaciones();
            $clientes=$this->clienteRep->obtenerClientesPorReserva();
            $estado_reserva=$this->estadoReservaRep->obtenerEstadoReservas();
            $tipo_habitacion=$this->tipoHabitacionRep->obtenerTipoHabitaciones();
            return view('business.reporte.reservas',['habitaciones'=>$habitaciones,'tipo_habitacion'=>$tipo_habitacion,'clientes'=>$clientes,'estado_reserva'=>$estado_reserva]);
        }
    }

    public function exportarReporteReservas(Request $request)
    {
        $formato=$request->get("formato");
        $habitacion_id=$request->get("habitacion_id");
        $cliente_id=$request->get("cliente_id");
        $estado_reserva_id=$request->get("estado_reserva_id");
        $tipo_habitacion_id=$request->get("tipo_habitacion_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
        $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
        $this->reporteRep->exportarReporteReservas($formato,$habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin);
    }

    //BEGIN: Reporte huespedes
    public function obtenerHuespedes(Request $request){
        if($request->ajax()){
            $habitacion_id=$request->get("habitacion_id");
            $estado_huesped_id=$request->get("estado_huesped_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            return $this->reporteRep->obtenerHuespedesDataTables($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin);
        }else{
            $habitaciones=$this->habitacionRep->obtenerHabitaciones();
            $estado_huesped=$this->estadoHuespedRep->obtenerEstadoHuesped();
            return view('business.reporte.huespedes',['habitaciones'=>$habitaciones,'estado_huesped'=>$estado_huesped]);
        }
    }

    public function exportarReporteHuespedes(Request $request)
    {
        $formato=$request->get("formato");
        $habitacion_id=$request->get("habitacion_id");
        $estado_huesped_id=$request->get("estado_huesped_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $this->reporteRep->exportarReporteHuespedes($formato,$habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin);
    }
    //END: Reporte huespedes

    //BEGIN: Reporte SIAT
    public function obtenerReporteSiat(Request $request){
        if($request->ajax()){
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            return $this->reporteRep->obtenerReporteSiatDataTables($fecha_ini,$fecha_fin);
        }else{
            return view('business.reporte.siat');
        }
    }

    public function exportarReporteSiat(Request $request)
    {
        $formato=$request->get("formato");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $this->reporteRep->exportarReporteSiat($formato,$fecha_ini,$fecha_fin);
    }
    //END: Reporte huespedes

}
