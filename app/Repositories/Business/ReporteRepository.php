<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Exports\ExportarReservasExcel;
use App\Exports\ExportarReservasPdf;
use App\Exports\ExportarHuespedesExcel;
use App\Exports\ExportarHuespedesPdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ReporteRepository{

    protected $exportarReservasExcel;
    protected $exportarReservasPdf;
    protected $exportarHuespedesExcel;
    protected $exportarHuespedesPdf;

    public function __construct(ExportarReservasExcel $exportarReservasExcel,ExportarReservasPdf $exportarReservasPdf,ExportarHuespedesExcel $exportarHuespedesExcel,ExportarHuespedesPdf $exportarHuespedesPdf){
        $this->exportarReservasExcel=$exportarReservasExcel;
        $this->exportarReservasPdf=$exportarReservasPdf;
        $this->exportarHuespedesExcel=$exportarHuespedesExcel;
        $this->exportarHuespedesPdf=$exportarHuespedesPdf;
    }

    //BEGIN:Reporte de reservas
    public function obtenerReservas($habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin){
        $reservas= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->leftjoin('res_servicio as serv','serv.id','=','r.servicio_id')
        ->leftjoin('gob_tipo_habitacion as th','th.id','=','h.tipo_habitacion_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('r.id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),DB::raw('(SELECT IFNULL(count(*),0) FROM res_huesped i inner join cli_cliente ci on i.cliente_id=ci.id WHERE i.estado_huesped_id=1 AND i.reserva_id=r.id AND i.estado=1 AND ci.estado=1) as huesped_checkin'),DB::raw('(SELECT IFNULL(count(*),0) FROM res_huesped o inner join cli_cliente co on o.cliente_id=co.id WHERE o.estado_huesped_id=2 AND o.reserva_id=r.id AND o.estado=1 AND co.estado=1) as huesped_checkout'),DB::raw('(SELECT IFNULL(count(*),0) FROM res_huesped t join cli_cliente ct on t.cliente_id=ct.id WHERE t.estado_huesped_id!=0 AND t.reserva_id=r.id AND t.estado=1 AND ct.estado=1) as huesped_total'),'h.num_habitacion','th.descripcion as tipo_habitacion','serv.descripcion as servicio',DB::raw('DATE_FORMAT(r.fecha_ini,"%d/%m/%Y") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%d/%m/%Y") as fecha_fin'),'cp.descripcion as pais','cc.descripcion as ciudad','r.detalle','er.descripcion as estado_reserva','r.servicio_id')
        ->whereRaw("(DATE_FORMAT(r.fecha_ini,'%Y%m%d') >='".$fecha_ini."' or '".$fecha_ini."' = -1)")
        ->whereRaw("(DATE_FORMAT(r.fecha_ini,'%Y%m%d') <='".$fecha_fin."' or '".$fecha_fin."' = -1)")
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->orderBy('r.id','desc');

        if($habitacion_id!=null){
            $reservas->where('r.habitacion_id','=',$habitacion_id);
        }

        if($tipo_habitacion_id!=null){
            $reservas->where('h.tipo_habitacion_id','=',$tipo_habitacion_id);
        }

        if($cliente_id!=null){
            $reservas->where('r.cliente_id','=',$cliente_id);
        }

        if($estado_reserva_id!=null){
            $reservas->where('r.estado_reserva_id','=',$estado_reserva_id);
        }

        return $reservas->get();
    }

    public function obtenerReservasDataTables($habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin){
        if($fecha_ini!=-1){
            $fecha_ini=Carbon::createFromFormat('d/m/Y H:i:s',$fecha_ini.' 00:00:00')->format('Ymd');
        }

        if($fecha_fin!=-1){
            $fecha_fin=Carbon::createFromFormat('d/m/Y H:i:s',$fecha_fin.' 00:00:00')->format('Ymd');
        }
        $reservas=$this->obtenerReservas($habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin);
        return datatables()->of($reservas)->toJson();
    }

    public function exportarReporteReservas($formato,$habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin){
       //Fecha para el reporte
       if($fecha_ini!=-1){
        $fecha_ini_dto=Carbon::createFromFormat('d/m/Y H:i:s',$fecha_ini.' 00:00:00')->format('d/m/Y');
        } else {
            $fecha_ini_dto='Inicio';
        }

        if($fecha_fin!=-1){
            $fecha_fin_dto=Carbon::createFromFormat('d/m/Y H:i:s',$fecha_fin.' 00:00:00')->format('d/m/Y');
        } else {
            $fecha_fin_dto='Ultimo';
        }

        //Fecha para realziar el filtro
        if($fecha_ini!=-1){
            $fecha_ini=Carbon::createFromFormat('d/m/Y H:i:s',$fecha_ini.' 00:00:00')->format('Ymd');
        }

        if($fecha_fin!=-1){
            $fecha_fin=Carbon::createFromFormat('d/m/Y H:i:s',$fecha_fin.' 00:00:00')->format('Ymd');
        }

        $reservas=$this->obtenerReservas($habitacion_id,$tipo_habitacion_id,$cliente_id,$estado_reserva_id,$fecha_ini,$fecha_fin);

        if($formato=="excel"){
            $this->exportarReservasExcel->exportar($reservas);
        } else {
            $this->exportarReservasPdf->exportar($reservas,$fecha_ini_dto,$fecha_fin_dto);
        }
    }
    //END:Reporte de reservas

    //BEGIN:Reporte de huespedes
    public function obtenerHuespedes($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin){
        $fecha_ini=($fecha_ini!=null)?Carbon::createFromFormat('Y-m-d',$fecha_ini)->format('Ymd'):null;
        $fecha_fin=($fecha_fin!=null)?Carbon::createFromFormat('Y-m-d',$fecha_fin)->format('Ymd'):null;

        $huespedes= DB::table('res_huesped as u')
        ->join('bas_persona as p','p.id','=','u.cliente_id')
        ->join('cli_cliente as c','c.id','=','u.cliente_id')
        ->leftjoin('cli_profesion as f','f.id','=','c.profesion_id')
        ->join('res_reserva as r','r.id','=','u.reserva_id')
        ->leftjoin('res_estado_huesped as e','e.id','=','u.estado_huesped_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('u.id','u.reserva_id',DB::raw('(CASE WHEN u.estado_huesped_id=2  THEN DATE_FORMAT(u.fecha_salida,"%Y%m%d")  ELSE  (CASE WHEN DATE_FORMAT(CURRENT_DATE(),"%Y%m%d") BETWEEN DATE_FORMAT(r.fecha_ini,"%Y%m%d") AND DATE_FORMAT(r.fecha_fin,"%Y%m%d") THEN DATE_FORMAT(CURRENT_DATE(),"%Y%m%d") ELSE DATE_FORMAT( u.fecha_ingreso,"%Y%m%d") END) END) AS fecha'),DB::raw('DATE_FORMAT(u.fecha_ingreso,"%d/%m/%Y") as fecha_ingreso'),DB::raw('DATE_FORMAT(u.fecha_salida,"%d/%m/%Y") as fecha_salida'),DB::raw('CONCAT(IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) AS huesped'),'h.num_habitacion','cp.descripcion as pais','cc.descripcion as ciudad','f.descripcion as profesion',DB::raw('TIMESTAMPDIFF(YEAR,p.fecha_nac, CURDATE()) as edad'),'p.doc_id','u.estado_huesped_id','e.descripcion as estado_huesped')
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('u.estado','=','1')
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('c.estado','=','1')
        ->orderBy('u.reserva_id','desc')
        ->havingRaw('fecha BETWEEN ? AND ?', [$fecha_ini, $fecha_fin]);

        if($habitacion_id!=null){
            $huespedes->where('r.habitacion_id','=',$habitacion_id);
        }

        if($estado_huesped_id!=null){
            $huespedes->where('u.estado_huesped_id','=',$estado_huesped_id);
        }

        $huespedes=$huespedes->get();
        return $huespedes;
    }

    public function obtenerHuespedesDataTables($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin){
        $huespedes=$this->obtenerHuespedes($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin);
        return datatables()->of($huespedes)->toJson();
    }

    public function exportarReporteHuespedes($formato,$habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin){
         $huespedes=$this->obtenerHuespedes($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin);
        //  $fecha_ini=($fecha_ini!=null)?Carbon::createFromFormat('Y-m-d',$fecha_ini)->format('d/m/Y'):null;
        //  $fecha_fin=($fecha_fin!=null)?Carbon::createFromFormat('Y-m-d',$fecha_fin)->format('d/m/Y'):null;
         if($formato=="excel"){
             $this->exportarHuespedesExcel->exportar($huespedes);
         } else {
             $huespedes = $huespedes->sortBy([['estado_huesped_id','asc'],['fecha_ingreso','asc']]);
             $this->exportarHuespedesPdf->exportar($huespedes,$fecha_ini,$fecha_fin);
         }
    }
    //END:Reporte de huespedes

}
