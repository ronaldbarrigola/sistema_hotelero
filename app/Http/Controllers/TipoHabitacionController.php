<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Repositories\Business\TipoHabitacionRepository;

class TipoHabitacionController extends Controller
{
    protected $tipoHabitacionRep;

    //===constructor=============================================================================================
    public function __construct(TipoHabitacionRepository $tipoHabitacionRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->tipoHabitacionRep=$tipoHabitacionRep;
    }

     //===========================================================================================================
     public function index(Request $request){
        if($request->ajax()){
           return $this->tipoHabitacionRep->obtenerTipoHabitacionesDataTables();
        }else{
           return view('business.tipo_habitacion.index');
        }
    }

    //================================================================================================
    public function store(Request $request){
        $tipo_habitacion=$this->tipoHabitacionRep->insertarDesdeRequest($request);
        return response()->json(array ('tipo_habitacion'=>$tipo_habitacion));
    }

    //================================================================================================
    public function edit(Request $request){
        $id=$request['tipo_habitacion_id'];
        $tipo_habitacion=$this->tipoHabitacionRep->obtenerTipoHabitacionPorId($id);
        return response()->json(array ('tipo_habitacion'=>$tipo_habitacion));
    }

    //================================================================================================
    public function update(Request $request,$id){
        $request->request->add(['tipo_habitacion_id'=>$id]);
        $tipo_habitacion=$this->tipoHabitacionRep->modificarDesdeRequest($request);
        return  $tipo_habitacion;
    }

    public function destroy(Request $request,$id){
        $tipo_habitacion=$this->tipoHabitacionRep->eliminar($id);
        return response()->json(array ('tipo_habitacion'=>$tipo_habitacion));
    }

}
