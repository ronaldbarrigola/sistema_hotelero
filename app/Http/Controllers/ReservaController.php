<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Entidades\Business\TransaccionPago;
use App\Repositories\Business\EstadoReservaRepository;
use App\Repositories\Business\HabitacionRepository;
use App\Repositories\Business\MotivoRepository;
use App\Repositories\Business\PaqueteRepository;
use App\Repositories\Business\ServicioRepository;
use App\Repositories\Business\ReservaRepository;
use App\Repositories\Business\CanalReservaRepository;
use App\Repositories\Business\TransaccionRepository;
use App\Repositories\Business\TransaccionAnticipoRepository;
//Para cliente
use App\Repositories\Business\ClienteCiudadRepository;
use App\Repositories\Business\ClienteRepository;
use App\Repositories\Business\PaisRepository;


use Carbon\Carbon;

class ReservaController extends Controller
{
    protected $clienteRep;
    protected $estadoReservaRep;
    protected $habitacionRep;
    protected $motivoRep;
    protected $paqueteRep;
    protected $servicioRep;
    protected $reservaRep;
    protected $canalReservaRep;
    protected $paisRep;
    protected $ciudadRep;
    protected $transaccionRep;
    protected $transaccionAnticipoRep;

    //===constructor=============================================================================================
    public function __construct(ClienteRepository $clienteRep,ReservaRepository $reservaRep,EstadoReservaRepository $estadoReservaRep,HabitacionRepository $habitacionRep,MotivoRepository $motivoRep,PaqueteRepository $paqueteRep,ServicioRepository $servicioRep,CanalReservaRepository $canalReservaRep,PaisRepository $paisRep,ClienteCiudadRepository $ciudadRep,TransaccionRepository $transaccionRep,TransaccionAnticipoRepository $transaccionAnticipoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->clienteRep=$clienteRep;
        $this->estadoReservaRep=$estadoReservaRep;
        $this->habitacionRep=$habitacionRep;
        $this->motivoRep=$motivoRep;
        $this->paqueteRep=$paqueteRep;
        $this->servicioRep=$servicioRep;
        $this->reservaRep=$reservaRep;
        $this->canalReservaRep=$canalReservaRep;
        $this->paisRep=$paisRep;
        $this->ciudadRep=$ciudadRep;
        $this->transaccionRep=$transaccionRep;
        $this->transaccionAnticipoRep=$transaccionAnticipoRep;
    }

     //===========================================================================================================
     public function index(Request $request){
        if($request->ajax()){
            $reservas=$this->reservaRep->obtenerReservasDataTables();
            return $reservas;
        }else{
            return view('business.reserva.index');
        }
    }

    public function estadoReserva(Request $request){
        $id=$request['reserva_id'];
        $estado=$request['estado_reserva_id'];
        $estadoReserva=$this->reservaRep->estadoReserva($id,$estado);
        return $estadoReserva;
    }

    public function obtenerReservaPorId(Request $request){
        $id=$request['reserva_id'];
        $reserva=$this->reservaRep->obtenerReservaPorId($id);
        $habitacion=$reserva->habitacion;
        $cliente=$reserva->cliente->persona->nombre_completo();
        return response()->json(array ('reserva'=>$reserva,'habitacion'=>$habitacion,'cliente'=>$cliente));
     }

    public function create(){
        $clientes=$this->clienteRep->obtenerClientes();
        $estadoReservas=$this->estadoReservaRep->obtenerEstadoReservas();
        $habitaciones=$this->habitacionRep->obtenerHabitaciones();
        $motivos=$this->motivoRep->obtenerMotivos();
        $paquetes=$this->paqueteRep->obtenerPaquetes();
        $servicios=$this->servicioRep->obtenerServicios();
        $canalReserva=$this->canalReservaRep->obtenerCanalReserva();
        $paises=$this->paisRep->obtenerPaises();
        return response()->json(array ('clientes'=>$clientes,'estadoReservas'=>$estadoReservas,'habitaciones'=>$habitaciones,'motivos'=>$motivos,'paquetes'=>$paquetes,'servicios'=>$servicios,'canal_reserva'=>$canalReserva,'paises'=>$paises));
    }

    public function store(Request $request){
        $reserva=$this->reservaRep->insertarDesdeRequest($request);
        $persona=$reserva->cliente->persona;
        $estadoReserva=$reserva->estadoReserva;
        return response()->json(array ('reserva'=>$reserva,'persona'=>$persona,'estadoReserva'=>$estadoReserva));
    }

    public function edit(Request $request){
        $id=$request['reserva_id'];
        $reserva=$this->reservaRep->obtenerReservaPorId($id);
        $transaccion=$this->reservaRep->transaccionPorReservaId($id);
        $transaccionPago=$this->transaccionAnticipoRep->obtenerAnticipoPorTransaccionId($transaccion->id);
        if(is_null($transaccionPago)){
           $transaccionPago=new TransaccionPago();
        }
        $clientes=$this->clienteRep->obtenerClientes();
        $estadoReservas=$this->estadoReservaRep->obtenerEstadoReservas();
        $habitaciones=$this->habitacionRep->obtenerHabitaciones();
        $motivos=$this->motivoRep->obtenerMotivos();
        $paquetes=$this->paqueteRep->obtenerPaquetes();
        $servicios=$this->servicioRep->obtenerServicios();
        $canalReserva=$this->canalReservaRep->obtenerCanalReserva();
        $paises=$this->paisRep->obtenerPaises();

        $ciudades=null;
        if( $reserva!=null){
            $ciudades=$this->ciudadRep->obtenerCiudadesPorPaisId($reserva->procedencia_pais_id);
        }
        return response()->json(array ('reserva'=>$reserva,'transaccion'=>$transaccion,'transaccion_pago'=>$transaccionPago,'ciudades'=>$ciudades,'clientes'=>$clientes,'estadoReservas'=>$estadoReservas,'habitaciones'=>$habitaciones,'motivos'=>$motivos,'paquetes'=>$paquetes,'servicios'=>$servicios,'canal_reserva'=>$canalReserva,'paises'=>$paises));
    }

    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $reserva=$this->reservaRep->modificarDesdeRequest($request);
        $persona=$reserva->cliente->persona;
        $estadoReserva=$reserva->estadoReserva;
        return response()->json(array ('reserva'=>$reserva,'persona'=>$persona,'estadoReserva'=>$estadoReserva));
    }

    public function validarEliminacion(Request $request){
        $id=$request["reserva_id"];
        $reserva=$this->reservaRep->validarEliminacion($id);
        return $reserva;
    }

    public function destroy(Request $request,$id){
        $reserva=$this->reservaRep->eliminar($id);
        return $reserva;
    }

    public function obtenerReservasTimeLines(){
        $response=true;
        $reservas=$this->reservaRep->obtenerReservasTimeLines();
        if (is_null($reservas) ){
          $response=false;
        }
        return response()->json(array('reservas'=>$reservas,'response'=>$response));
    }

    public function obtenerReservasPorIdTimeLines(Request $request){ //Usado para actualizar datos de pago del item en la linea de tiempo
        $id=$request["reserva_id"];
        $response=true;
        $reserva=$this->reservaRep->obtenerReservasPorIdTimeLines($id);
        if (is_null($reserva) ){
          $response=false;
        }
        return response()->json(array('reserva'=>$reserva,'response'=>$response));
    }

    public function generarComprobanteDetalleCargo(Request $request){
        $id=$request["reserva_id"];
        $response=true;
        $reserva=$this->reservaRep->generarComprobanteDetalleCargo($id);
        if (is_null($reserva) ){
          $response=false;
        }
        return response()->json(array('reserva'=>$reserva,'response'=>$response));
    }

}
