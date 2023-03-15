<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Business\ClienteRepository;
use App\Repositories\Business\EstadoReservaRepository;
use App\Repositories\Business\HabitacionRepository;
use App\Repositories\Business\MotivoRepository;
use App\Repositories\Business\PaqueteRepository;
use App\Repositories\Business\ReservaRepository;
use App\Repositories\Business\CiudadRepository;
use App\Repositories\Business\PaisRepository;
use Carbon\Carbon;

class ReservaController extends Controller
{
    protected $clienteRep;
    protected $estadoReservaRep;
    protected $habitacionRep;
    protected $motivoRep;
    protected $paqueteRep;
    protected $reservaRep;
    protected $paisRep;
    protected $ciudadRep;

    //===constructor=============================================================================================
    public function __construct(ClienteRepository $clienteRep,ReservaRepository $reservaRep,EstadoReservaRepository $estadoReservaRep,HabitacionRepository $habitacionRep,MotivoRepository $motivoRep,PaqueteRepository $paqueteRep,PaisRepository $paisRep,CiudadRepository $ciudadRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->clienteRep=$clienteRep;
        $this->estadoReservaRep=$estadoReservaRep;
        $this->habitacionRep=$habitacionRep;
        $this->motivoRep=$motivoRep;
        $this->paqueteRep=$paqueteRep;
        $this->reservaRep=$reservaRep;
        $this->paisRep=$paisRep;
        $this->ciudadRep=$ciudadRep;
    }

     //===========================================================================================================
     public function index(Request $request){
        if($request->ajax()){
            return $this->reservaRep->obtenerReservasDataTables();
        }else{
            $cliente=$this->clienteRep->obtenerClientes();
            $estadoReservas=$this->estadoReservaRep->obtenerEstadoReservas();
            $habitaciones=$this->habitacionRep->obtenerHabitaciones();
            $motivos=$this->motivoRep->obtenerMotivos();
            $paquetes=$this->paqueteRep->obtenerPaquetes();
            $paises=$this->paisRep->obtenerPaises();
            $ciudades=$this->ciudadRep->obtenerCiudades();
            return view('business.reserva.index',['cliente'=>$cliente,'estadoReservas'=>$estadoReservas,'habitaciones'->$habitaciones,'motivos'->$motivos,'paquetes'->$paquetes,'paises'=>$paises,'ciudades'=>$ciudades]);
        }
    }

    //================================================================================================
    public function store(Request $request){
        $reserva=$this->reservaRep->insertarDesdeRequest($request);
        return response()->json(array ('reserva'=>$reserva));
    }

    //================================================================================================
    public function edit(Request $request){
        $id=$request['reserva_id'];
        $reserva=$this->reservaRep->obtenerReservaPorId($id);

        $ciudades=null;
        if( $reserva!=null){
            $ciudades=$this->ciudadRep->obtenerCiudadesPorPaisId($reserva->procedencia_pais_id);
        }

        return response()->json(array('reserva'=>$reserva,'ciudades'=>$ciudades));
    }

    //================================================================================================
    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $reserva=$this->reservaRep->modificarDesdeRequest($request);
        return  $reserva;
    }

   //================================================================================================
    public function destroy(Request $request,$id){
        $reserva=$this->reservaRep->eliminar($id);

        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'reserva ' . $reserva->id. ', eliminada',
                'id'      => $reserva->id
            ));
        }

        return Redirect::route('business.reserva.index');
    }

}
