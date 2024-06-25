<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Exports\ExportarReservasExcel;
use App\Exports\ExportarReservasPdf;
use App\Exports\ExportarHuespedesExcel;
use App\Exports\ExportarHuespedesPdf;
use App\Exports\ExportarProduccionExcel;
use App\Exports\ExportarProduccionPdf;
use App\Exports\ExportarSiatExcel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ReporteRepository{

    protected $exportarReservasExcel;
    protected $exportarReservasPdf;
    protected $exportarHuespedesExcel;
    protected $exportarHuespedesPdf;
    protected $exportarProduccionExcel;
    protected $exportarProduccionPdf;
    protected $exportarSiatExcel;

    public function __construct(ExportarReservasExcel $exportarReservasExcel,ExportarReservasPdf $exportarReservasPdf,ExportarHuespedesExcel $exportarHuespedesExcel,ExportarHuespedesPdf $exportarHuespedesPdf,ExportarProduccionExcel $exportarProduccionExcel,ExportarProduccionPdf $exportarProduccionPdf,ExportarSiatExcel $exportarSiatExcel){
        $this->exportarReservasExcel=$exportarReservasExcel;
        $this->exportarReservasPdf=$exportarReservasPdf;
        $this->exportarHuespedesExcel=$exportarHuespedesExcel;
        $this->exportarHuespedesPdf=$exportarHuespedesPdf;
        $this->exportarProduccionExcel=$exportarProduccionExcel;
        $this->exportarProduccionPdf=$exportarProduccionPdf;
        $this->exportarSiatExcel=$exportarSiatExcel;
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

        $fecha_actual=Carbon::now('America/La_Paz')->format('Ymd');
        $fechaActualDto=Carbon::parse($fecha_actual);
        $fechaFinDto=Carbon::parse($fecha_fin);

        if ($fechaFinDto->greaterThan($fechaActualDto)) { //La fecha 2 es mayor que la fecha 1
            $fecha_fin=$fecha_actual;
        }

        $huespedes_salida= DB::table('res_huesped as u')
        ->join('bas_persona as p','p.id','=','u.cliente_id')
        ->join('cli_cliente as c','c.id','=','u.cliente_id')
        ->leftjoin('cli_profesion as f','f.id','=','c.profesion_id')
        ->join('res_reserva as r','r.id','=','u.reserva_id')
        ->leftjoin('res_estado_huesped as e','e.id','=','u.estado_huesped_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('u.id','u.reserva_id',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y%m%d") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y%m%d") as fecha_fin'),DB::raw('DATE_FORMAT(u.fecha_ingreso,"%d/%m/%Y") as fecha_ingreso'),DB::raw('DATE_FORMAT(u.fecha_salida,"%d/%m/%Y") as fecha_salida'),DB::raw('CONCAT(IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) AS huesped'),'h.num_habitacion','cp.descripcion as pais','cc.descripcion as ciudad','f.descripcion as profesion',DB::raw('TIMESTAMPDIFF(YEAR,p.fecha_nac, CURDATE()) as edad'),'p.doc_id','u.estado_huesped_id','e.descripcion as estado_huesped',DB::raw('"SALIDA" as movimiento'))
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('u.estado','=','1')
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('c.estado','=','1')
        ->where('u.estado_huesped_id','=',2)
        ->whereRaw('DATE_FORMAT(u.fecha_salida,"%Y%m%d")>=?', [$fecha_fin])
        ->whereRaw('DATE_FORMAT(u.fecha_salida,"%Y%m%d") BETWEEN ? AND ?', [$fecha_ini,$fecha_fin]);

        if($habitacion_id!=null){
            $huespedes_salida->where('r.habitacion_id','=',$habitacion_id);
        }
        if($estado_huesped_id!=null){
            $huespedes_salida->where('u.estado_huesped_id','=',$estado_huesped_id);
        }
        $huespedes_salida=$huespedes_salida->get();


        $excluir_salida = $huespedes_salida->pluck('id')->toArray();

        $huespedes_ingreso= DB::table('res_huesped as u')
        ->join('bas_persona as p','p.id','=','u.cliente_id')
        ->join('cli_cliente as c','c.id','=','u.cliente_id')
        ->leftjoin('cli_profesion as f','f.id','=','c.profesion_id')
        ->join('res_reserva as r','r.id','=','u.reserva_id')
        ->leftjoin('res_estado_huesped as e','e.id','=','u.estado_huesped_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('u.id','u.reserva_id',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y%m%d") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y%m%d") as fecha_fin'),DB::raw('DATE_FORMAT(u.fecha_ingreso,"%d/%m/%Y") as fecha_ingreso'),DB::raw('DATE_FORMAT(u.fecha_salida,"%d/%m/%Y") as fecha_salida'),DB::raw('CONCAT(IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) AS huesped'),'h.num_habitacion','cp.descripcion as pais','cc.descripcion as ciudad','f.descripcion as profesion',DB::raw('TIMESTAMPDIFF(YEAR,p.fecha_nac, CURDATE()) as edad'),'p.doc_id','u.estado_huesped_id','e.descripcion as estado_huesped',DB::raw('"INGRESO" as movimiento'))
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->whereNotIn('u.id', $excluir_salida)
        ->where('u.estado','=','1')
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('c.estado','=','1')
        ->whereRaw('DATE_FORMAT(u.fecha_ingreso,"%Y%m%d")<=?', [$fecha_fin])
        ->whereRaw('DATE_FORMAT(u.fecha_ingreso,"%Y%m%d") BETWEEN ? AND ?', [$fecha_ini,$fecha_fin]);
        if($habitacion_id!=null){
            $huespedes_ingreso->where('r.habitacion_id','=',$habitacion_id);
        }
        if($estado_huesped_id!=null){
            $huespedes_ingreso->where('u.estado_huesped_id','=',$estado_huesped_id);
        }
        $huespedes_ingreso=$huespedes_ingreso->get();

        $excluir_ingreso = $huespedes_ingreso->pluck('id')->toArray();

        $huespedes_permanencia= DB::table('res_huesped as u')
        ->join('bas_persona as p','p.id','=','u.cliente_id')
        ->join('cli_cliente as c','c.id','=','u.cliente_id')
        ->leftjoin('cli_profesion as f','f.id','=','c.profesion_id')
        ->join('res_reserva as r','r.id','=','u.reserva_id')
        ->leftjoin('res_estado_huesped as e','e.id','=','u.estado_huesped_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('u.id','u.reserva_id',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y%m%d") as fecha_ini'),DB::raw('(CASE WHEN u.estado_huesped_id=2 THEN DATE_FORMAT(u.fecha_salida,"%Y%m%d") ELSE DATE_FORMAT(r.fecha_fin,"%Y%m%d") END) as fecha_fin'),DB::raw('DATE_FORMAT(u.fecha_ingreso,"%d/%m/%Y") as fecha_ingreso'),DB::raw('DATE_FORMAT(u.fecha_salida,"%d/%m/%Y") as fecha_salida'),DB::raw('CONCAT(IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) AS huesped'),'h.num_habitacion','cp.descripcion as pais','cc.descripcion as ciudad','f.descripcion as profesion',DB::raw('TIMESTAMPDIFF(YEAR,p.fecha_nac, CURDATE()) as edad'),'p.doc_id','u.estado_huesped_id','e.descripcion as estado_huesped',DB::raw('"PERMANENCIA" as movimiento'))
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->whereNotIn('u.id', $excluir_ingreso)
        ->whereNotIn('u.id', $excluir_salida)
        ->where('u.estado','=','1')
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('c.estado','=','1')
        //->whereRaw('? BETWEEN DATE_FORMAT(r.fecha_ini,"%Y%m%d") AND DATE_FORMAT(r.fecha_fin,"%Y%m%d")',[$fecha_fin]);
        ->havingRaw('? BETWEEN DATE_FORMAT(fecha_ini,"%Y%m%d") AND fecha_fin',[$fecha_fin]);
        if($habitacion_id!=null){
            $huespedes_permanencia->where('r.habitacion_id','=',$habitacion_id);
        }
        if($estado_huesped_id!=null){
            $huespedes_permanencia->where('u.estado_huesped_id','=',$estado_huesped_id);
        }
        $huespedes_permanencia=$huespedes_permanencia->get();

        $huespedes = $huespedes_salida->merge($huespedes_ingreso)->merge($huespedes_permanencia);
        return $huespedes;
    }

    public function obtenerHuespedesDataTables($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin){
        $huespedes=$this->obtenerHuespedes($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin);
        return datatables()->of($huespedes)->toJson();
    }

    public function exportarReporteHuespedes($formato,$habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin){
         $huespedes=$this->obtenerHuespedes($habitacion_id,$estado_huesped_id,$fecha_ini,$fecha_fin);
         if($formato=="excel"){
             $this->exportarHuespedesExcel->exportar($huespedes);
         } else {
             $huespedes = $huespedes->sortBy([['estado_huesped_id','asc'],['fecha_ingreso','asc']]);
             $this->exportarHuespedesPdf->exportar($huespedes,$fecha_ini,$fecha_fin);
         }
    }
    //END:Reporte de huespedes

    //BEGIN:Reporte SIAT
    public function obtenerReporteSiat($fecha_ini,$fecha_fin){
        $fecha_ini=($fecha_ini!=null)?Carbon::createFromFormat('Y-m-d',$fecha_ini)->format('Ymd'):null;
        $fecha_fin=($fecha_fin!=null)?Carbon::createFromFormat('Y-m-d',$fecha_fin)->format('Ymd'):null;

        $huespedes = DB::table('res_huesped as u')
        ->join('cli_cliente as cli', 'cli.id', '=', 'u.cliente_id')
        ->join('bas_persona as p', 'p.id', '=', 'cli.id')
        ->leftJoin('cli_pais as cp', 'cp.id', '=', 'cli.pais_id')
        ->leftjoin('con_cliente_datofactura as df', 'df.cliente_id', '=', 'p.id')
        ->leftjoin('con_datofactura as f', 'f.id', '=', 'df.datofactura_id')
        ->select([
            'p.doc_id',
            'cp.descripcion as nacionalidad',
            DB::raw('CONCAT(IFNULL(p.paterno, ""), " ", IFNULL(p.materno, ""), " ", IFNULL(p.nombre, "")) AS huesped'),
            DB::raw('DATE_FORMAT(u.fecha_ingreso, "%d/%m/%Y") as fecha_ingreso'),
            DB::raw('DATE_FORMAT(u.fecha_salida, "%d/%m/%Y") as fecha_salida'),
            DB::raw('"0" as nro_factura'),
            DB::raw('"0" as nro_autorizacion'),
            DB::raw('"" as observacion'),
            DB::raw('"" as justificacion'),
            'f.nit'
        ])
        ->where('u.estado', '=', '1')
        ->where('cli.estado', '=', '1')
        ->where('p.estado', '=', '1')
        ->where('u.estado_huesped_id', '=', 2)
        ->whereBetween(DB::raw('DATE_FORMAT(u.fecha_ingreso, "%Y%m%d")'), [$fecha_ini, $fecha_fin])
        ->orderBy('u.id', 'desc')
        ->distinct()
        ->get();

        return $huespedes;
    }

    public function obtenerReporteSiatDataTables($fecha_ini,$fecha_fin){
        $huespedes=$this->obtenerReporteSiat($fecha_ini,$fecha_fin);
        return datatables()->of($huespedes)->toJson();
    }

    public function exportarReporteSiat($fecha_ini,$fecha_fin){
        $huespedes=$this->obtenerReporteSiat($fecha_ini,$fecha_fin);
        $this->exportarSiatExcel->exportar($huespedes);
    }
    //END:Reporte SIAT

    //BEGIN:Reporte Produccion
    public function obtenerProduccion($habitacion_id,$producto_id,$canal_reserva_id,$fecha_ini,$fecha_fin){
        $fecha_ini=($fecha_ini!=null)?Carbon::createFromFormat('Y-m-d',$fecha_ini)->format('Ymd'):null;
        $fecha_fin=($fecha_fin!=null)?Carbon::createFromFormat('Y-m-d',$fecha_fin)->format('Ymd'):null;

        $produccion=DB::table('res_reserva as r')
        ->leftjoin('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('res_canal_reserva as cr','cr.id','=','r.canal_reserva_id')
        ->join('con_transaccion as tr','tr.reserva_id','=','r.id')
        ->join('con_transaccion_pago as trp','trp.transaccion_id','=','tr.id')
        ->leftjoin('con_tipo_transaccion as tt','tt.id','=','trp.tipo_transaccion_id')
        ->join('con_pago as pg','pg.id','=','trp.pago_id')
        ->leftjoin('cli_cliente as cli','cli.id','=','pg.cliente_id')
        ->leftjoin('bas_persona as p','p.id','=','cli.id')
        ->leftjoin('pro_hotel_producto as hp','hp.id','=','tr.hotel_producto_id')
        ->leftjoin('pro_producto as pd','pd.id','=','hp.producto_id')
        ->select(DB::raw('DATE_FORMAT(trp.fecha,"%d/%m/%Y") as fecha'),'cr.nombre as canal_reserva','r.id as reserva_id','h.num_habitacion','pd.descripcion as producto',DB::raw('CONCAT(IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) AS cliente'),'tt.descripcion as tipo_transaccion','trp.monto')
        ->where('r.estado','=','1')
        ->where('tr.estado','=','1')
        ->where('trp.estado','=','1')
        ->where('pg.estado','=','1')
        ->whereRaw('DATE_FORMAT(trp.fecha,"%Y%m%d") BETWEEN ? AND ?', [$fecha_ini,$fecha_fin])
        ->orderBy('trp.id','desc');

        if($habitacion_id!=null){
            $produccion->where('h.id','=',$habitacion_id);
        }
        if($producto_id!=null){
            $produccion->where('pd.id','=',$producto_id);
        }
        if($canal_reserva_id!=null){
            $produccion->where('cr.id','=',$canal_reserva_id);
        }

        $produccion=$produccion->get();
        return $produccion;
    }

    public function obtenerReporteProduccionDataTables($habitacion_id,$producto_id,$canal_reserva_id,$fecha_ini,$fecha_fin){
        $produccion=$this->obtenerProduccion($habitacion_id,$producto_id,$canal_reserva_id,$fecha_ini,$fecha_fin);
        return datatables()->of($produccion)->toJson();
    }

    public function exportarReporteProduccion($formato,$habitacion_id,$producto_id,$canal_reserva_id,$fecha_ini,$fecha_fin){
        $produccion=$this->obtenerProduccion($habitacion_id,$producto_id,$canal_reserva_id,$fecha_ini,$fecha_fin);
        if($formato=="excel"){
            $this->exportarProduccionExcel->exportar($produccion);
        } else {
            $this->exportarProduccionPdf->exportar($produccion,$fecha_ini,$fecha_fin);
        }
    }

    //END:Reporte SIAT

}
