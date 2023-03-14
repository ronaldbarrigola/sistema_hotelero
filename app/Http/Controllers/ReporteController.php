<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Business\ReporteRepository;
use App\Repositories\Business\CategoriaRepository;
use App\Repositories\Business\MarcaRepository;
use App\Repositories\Business\TraspasoRepository;
use App\Repositories\Base\AgenciaRepository;
use App\Repositories\Business\FormaPagoRepository;
use App\Repositories\Base\UsuarioRepository;

class ReporteController extends Controller
{

    protected $reporteRep;
    protected $categoriaRep;
    protected $marcaRep;
    protected $traspasoRep;
    protected $agenciaRep;
    protected $formaPagoRep;
    protected $usuarioRep;

    public function __construct(ReporteRepository $reporteRep,CategoriaRepository $categoriaRep,MarcaRepository $marcaRep,TraspasoRepository $traspasoRep,AgenciaRepository $agenciaRep,FormaPagoRepository $formaPagoRep,UsuarioRepository $usuarioRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->reporteRep=$reporteRep;
        $this->categoriaRep=$categoriaRep;
        $this->marcaRep=$marcaRep;
        $this->traspasoRep=$traspasoRep;
        $this->agenciaRep=$agenciaRep;
        $this->formaPagoRep=$formaPagoRep;
        $this->usuarioRep=$usuarioRep;
    }

     //BEGIN: Reporte de ventas
    public function obtenerReporteVenta(Request $request){
        if($request->ajax()){
            $categoria_id=$request->get("categoria_id");
            $marca_id=$request->get("marca_id");
            $forma_pago_id=$request->get("forma_pago_id");
            $venta_id=$request->get("venta_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
            $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
            return $this->reporteRep->obtenerReporteVenta($categoria_id,$marca_id,$forma_pago_id,$venta_id,$fecha_ini,$fecha_fin);
        }else{
            $categoria=$this->categoriaRep->obtenerCategoria("");
            $marca=$this->marcaRep->obtenerMarca("");
            $forma_pago=$this->formaPagoRep->obtenerFormaPago();
            return view('business.reporte.ventas',['categoria'=>$categoria,'marca'=>$marca,'forma_pago'=>$forma_pago]);
        }
    }

    public function exportarReporteVenta(Request $request)
    {
        $formato=$request->get("formato");
        $categoria_id=$request->get("categoria_id");
        $marca_id=$request->get("marca_id");
        $forma_pago_id=$request->get("forma_pago_id");
        $venta_id=$request->get("venta_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
        $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;

        $this->reporteRep->exportarReporteVenta($formato,$categoria_id,$marca_id,$forma_pago_id,$venta_id,$fecha_ini,$fecha_fin);
    }

    public function obtenerReporteVentaGeneral(Request $request){
        if($request->ajax()){
            $usuario_id=$request->get("usuario_id");
            $categoria_id=$request->get("categoria_id");
            $marca_id=$request->get("marca_id");
            $forma_pago_id=$request->get("forma_pago_id");
            $agencia_id=$request->get("agencia_id");
            $venta_id=$request->get("venta_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
            $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
            return $this->reporteRep->obtenerReporteVentaGeneral($categoria_id,$marca_id,$forma_pago_id,$agencia_id,$venta_id,$fecha_ini,$fecha_fin,$usuario_id);
        }else{
            $categoria=$this->categoriaRep->obtenerCategoria("");
            $marca=$this->marcaRep->obtenerMarca("");
            $forma_pago=$this->formaPagoRep->obtenerFormaPago();
            $agencia=$this->agenciaRep->obtenerListaAgencias();
            $usuario=$this->usuarioRep->obtenerListaUsuariosAdmin();
            return view('business.reporte.ventageneral',['categoria'=>$categoria,'marca'=>$marca,'forma_pago'=>$forma_pago,'agencia'=>$agencia,'usuario'=>$usuario]);
        }
    }

    public function exportarReporteVentaGeneral(Request $request)
    {
        $formato=$request->get("formato");
        $usuario_id=$request->get("usuario_id");
        $categoria_id=$request->get("categoria_id");
        $marca_id=$request->get("marca_id");
        $forma_pago_id=$request->get("forma_pago_id");
        $agencia_id=$request->get("agencia_id");
        $venta_id=$request->get("venta_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
        $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;

        $this->reporteRep->exportarReporteVentaGeneral($formato,$categoria_id,$marca_id,$forma_pago_id,$agencia_id,$venta_id,$fecha_ini,$fecha_fin,$usuario_id);
    }
    //END: Reporte de ventas

    //BEGIN: Reporte de compras
    public function obtenerReporteCompra(Request $request){
        if($request->ajax()){
            $categoria_id=$request->get("categoria_id");
            $marca_id=$request->get("marca_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            $compra_id=$request->get("compra_id");
            $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
            $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
            return $this->reporteRep->obtenerReporteCompra($categoria_id,$marca_id,$fecha_ini,$fecha_fin,$compra_id);
        }else{
            $categoria=$this->categoriaRep->obtenerCategoria("");
            $marca=$this->marcaRep->obtenerMarca("");
            return view('business.reporte.compras',['categoria'=>$categoria,'marca'=>$marca]);
        }
    }

    public function exportarReporteCompra(Request $request)
    {
        $formato=$request->get("formato");
        $categoria_id=$request->get("categoria_id");
        $marca_id=$request->get("marca_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $compra_id=$request->get("compra_id");
        $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
        $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;

        $this->reporteRep->exportarReporteCompra($formato,$categoria_id,$marca_id,$fecha_ini,$fecha_fin,$compra_id);
    }
    //END: Reporte de compras


    //BEGIN: Reporte de inventario
    public function obtenerReporteInventario(Request $request){
        if($request->ajax()){
            $categoria_id=$request->get("categoria_id");
            $marca_id=$request->get("marca_id");
            $agencia_id=$request->get("agencia_id");

            return $this->reporteRep->obtenerReporteInventario($categoria_id,$marca_id,$agencia_id);
        }else{
            $categoria=$this->categoriaRep->obtenerCategoria("");
            $marca=$this->marcaRep->obtenerMarca("");
            $agencia=$this->agenciaRep->obtenerListaAgencias();
            return view('business.reporte.inventario',['categoria'=>$categoria,'marca'=>$marca,'agencia'=>$agencia]);
        }
    }

    public function exportarReporteInventario(Request $request)
    {
        $categoria_id=$request->get("categoria_id");
        $marca_id=$request->get("marca_id");
        $agencia_id=$request->get("agencia_id");

        $this->reporteRep->exportarReporteInventario($categoria_id,$marca_id,$agencia_id);
    }
    //END: Reporte de inventario


    //BEGIN Reporte de traspaso
    public function obtenerReporteTraspaso(Request $request){
        if($request->ajax()){
            $agencia_destino_id=$request->get("agencia_destino_id");
            $estado_traspaso_id=$request->get("estado_traspaso_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
            $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
            return $this->reporteRep->obtenerReporteTraspaso($agencia_destino_id,$estado_traspaso_id,$fecha_ini,$fecha_fin);
        } else {
            $estadotraspaso=$this->traspasoRep->obtenerEstadoTraspaso();
            $agencia=$this->agenciaRep->obtenerListaAgencias();
            return view('business.reporte.traspaso',['estadotraspaso'=>$estadotraspaso,'agencia'=>$agencia]);
        }
    }

    public function exportarReporteTraspaso(Request $request)
    {
        $formato=$request->get("formato");
        $agencia_destino_id=$request->get("agencia_destino_id");
        $estado_traspaso_id=$request->get("estado_traspaso_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
        $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;

        $this->reporteRep->exportarReporteTraspaso($formato,$agencia_destino_id,$estado_traspaso_id,$fecha_ini,$fecha_fin);
    }

    public function obtenerReporteTraspasoGeneral(Request $request){//Reporte traspaso general
        if($request->ajax()){
            $agencia_origen_id=$request->get("agencia_origen_id");
            $agencia_destino_id=$request->get("agencia_destino_id");
            $estado_traspaso_id=$request->get("estado_traspaso_id");
            $fecha_ini=$request->get("fecha_ini");
            $fecha_fin=$request->get("fecha_fin");
            $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
            $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;
            return $this->reporteRep->obtenerReporteTraspasoGeneral($agencia_origen_id,$agencia_destino_id,$estado_traspaso_id,$fecha_ini,$fecha_fin);
        } else {
            $estadotraspaso=$this->traspasoRep->obtenerEstadoTraspaso();
            $agencia=$this->agenciaRep->obtenerListaAgencias();
            return view('business.reporte.traspasogeneral',['estadotraspaso'=>$estadotraspaso,'agencia'=>$agencia]);
        }
    }

    public function exportarReporteTraspasoGeneral(Request $request)
    {
        $formato=$request->get("formato");
        $agencia_origen_id=$request->get("agencia_origen_id");
        $agencia_destino_id=$request->get("agencia_destino_id");
        $estado_traspaso_id=$request->get("estado_traspaso_id");
        $fecha_ini=$request->get("fecha_ini");
        $fecha_fin=$request->get("fecha_fin");
        $fecha_ini=($fecha_ini==""||$fecha_ini==null)?-1:$fecha_ini;
        $fecha_fin=($fecha_fin==""||$fecha_fin==null)?-1:$fecha_fin;

        $this->reporteRep->exportarReporteTraspasoGeneral($formato,$agencia_origen_id,$agencia_destino_id,$estado_traspaso_id,$fecha_ini,$fecha_fin);
    }

    //END Reporte de traspaso

}
