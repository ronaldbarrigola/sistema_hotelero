<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Business\TipoHabitacionRepository;
use App\Repositories\Base\AgenciaRepository;
use App\Repositories\Business\HabitacionRepository;
use App\Repositories\Business\EstadoHabitacionRepository;
use Carbon\Carbon;

class HabitacionController extends Controller
{
    protected $tipoHabitacionRep;
    protected $agenciaRep;
    protected $habitacionRep;
    protected $estadoHabitacionRep;

    //===constructor=============================================================================================
    public function __construct(HabitacionRepository $habitacionRep,TipoHabitacionRepository $tipoHabitacionRep,EstadoHabitacionRepository $estadoHabitacionRep,AgenciaRepository $agenciaRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->tipoHabitacionRep=$tipoHabitacionRep;
        $this->agenciaRep=$agenciaRep;
        $this->habitacionRep=$habitacionRep;
        $this->estadoHabitacionRep=$estadoHabitacionRep;
    }

     //===========================================================================================================
     public function index(Request $request){
        if($request->ajax()){
           return $this->habitacionRep->obtenerHabitacionesDataTables();
        }else{
           $agencias=$this->agenciaRep->obtenerListaAgencias();
           $tipoHabitaciones=$this->tipoHabitacionRep->obtenerTipoHabitaciones();
           $estadoHabitaciones=$this->estadoHabitacionRep->obtenerEstadoHabitaciones();
           return view('business.habitacion.index',['agencias'=>$agencias,'tipoHabitaciones'=>$tipoHabitaciones,'estadoHabitaciones'=>$estadoHabitaciones]);
        }
    }

    //================================================================================================
    public function store(Request $request){
        $habitacion=$this->habitacionRep->insertarDesdeRequest($request);
        return response()->json(array ('habitacion'=>$habitacion));
    }

    //================================================================================================
    public function edit(Request $request){
        $id=$request['habitacion_id'];
        $habitacion=$this->habitacionRep->obtenerHabitacionPorId($id);
        return response()->json(array ('habitacion'=>$habitacion));
    }

    //================================================================================================
    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $habitacion=$this->habitacionRep->modificarDesdeRequest($request);
        return  $habitacion;
    }

    public function  obtenerHabitaciones(){
       $response=true;
       $habitaciones=$this->habitacionRep->obtenerHabitaciones();
       if (is_null($habitaciones) ){
         $response=false;
       }
       return response()->json(array ('habitaciones'=>$habitaciones,'response'=>$response));
    }

    public function destroy(Request $request,$id){
        $habitacion=$this->habitacionRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'Habitacion ' . $habitacion->descripcion. ', eliminada',
                'id'      => $habitacion->id
            ));
        }

        return Redirect::route('business.habitacion.index');
    }

}
