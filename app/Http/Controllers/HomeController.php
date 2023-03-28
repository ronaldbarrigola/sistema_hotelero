<?php

namespace App\Http\Controllers;

use App\Repositories\Business\ClienteRepository;
use App\Repositories\Business\EstadoReservaRepository;
use App\Repositories\Business\HabitacionRepository;
use App\Repositories\Business\MotivoRepository;
use App\Repositories\Business\PaqueteRepository;
use App\Repositories\Business\ServicioRepository;
use App\Repositories\Business\ReservaRepository;
use App\Repositories\Business\PaisRepository;
use App\Repositories\Base\CiudadRepository;



class HomeController extends Controller
{
    protected $clienteRep;
    protected $estadoReservaRep;
    protected $habitacionRep;
    protected $motivoRep;
    protected $paqueteRep;
    protected $servicioRep;
    protected $reservaRep;
    protected $paisRep;
    protected $ciudadRep;

    //===constructor=============================================================================================
    public function __construct(ClienteRepository $clienteRep,ReservaRepository $reservaRep,EstadoReservaRepository $estadoReservaRep,HabitacionRepository $habitacionRep,MotivoRepository $motivoRep,PaqueteRepository $paqueteRep,ServicioRepository $servicioRep,PaisRepository $paisRep,CiudadRepository $ciudadRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->clienteRep=$clienteRep;
        $this->estadoReservaRep=$estadoReservaRep;
        $this->habitacionRep=$habitacionRep;
        $this->motivoRep=$motivoRep;
        $this->paqueteRep=$paqueteRep;
        $this->servicioRep=$servicioRep;
        $this->reservaRep=$reservaRep;
        $this->paisRep=$paisRep;
        $this->ciudadRep=$ciudadRep;
    }

    public function index()
    {
        $clientes=$this->clienteRep->obtenerClientes();
        $estadoReservas=$this->estadoReservaRep->obtenerEstadoReservas();
        $habitaciones=$this->habitacionRep->obtenerHabitaciones();
        $motivos=$this->motivoRep->obtenerMotivos();
        $paquetes=$this->paqueteRep->obtenerPaquetes();
        $servicios=$this->servicioRep->obtenerServicios();
        $paises=$this->paisRep->obtenerPaises();
        return view('home',['clientes'=>$clientes,'estadoReservas'=>$estadoReservas,'habitaciones'=>$habitaciones,'motivos'=>$motivos,'paquetes'=>$paquetes,'servicios'=>$servicios,'paises'=>$paises]);
    }

}
