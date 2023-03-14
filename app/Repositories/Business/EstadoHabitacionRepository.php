<?php

namespace App\Repositories\Business;
use Illuminate\Http\Request;
use App\Entidades\Business\EstadoHabitacion;
use Carbon\Carbon;
use DB;

class EstadoHabitacionRepository{

    public function obtenerEstadoHabitaciones(){
        $estadoHabitaciones=DB::table('gob_estado_habitacion as e')
            ->select('e.id','e.descripcion')
            ->where('e.estado','=','1')
            ->orderBy('e.id','asc')
            ->get();
        return $estadoHabitaciones;
    }

    public function obtenerEstadoHabitacionPorId($id){
        return EstadoHabitacion::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $estadoHabitacion=null;
        try{
            DB::beginTransaction();

            $estadoHabitacion=new EstadoHabitacion($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            $estadoHabitacion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $estadoHabitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $estadoHabitacion->estado=1;
            $estadoHabitacion->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $estadoHabitacion;
    }

    public function modificarDesdeRequest(Request $request){
        $estadoHabitacion=null;
        try{
            DB::beginTransaction();

            $estadoHabitacion=$this->obtenerEstadoHabitacionPorId($request->get('id'));
            $estadoHabitacion->fill($request->all()); //llena datos desde el array entrante en el request.
            $estadoHabitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $estadoHabitacion->update();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $estadoHabitacion;
    }

    public function eliminar($id){
        $estadoHabitacion=$this->obtenerEstadoHabitacionPorId($id);
        if ( is_null($estadoHabitacion) ){
           App::abort(404);
        }
        $estadoHabitacion->estado='0';
        $estadoHabitacion->update();
        return $estadoHabitacion;
    }

}//fin clase
