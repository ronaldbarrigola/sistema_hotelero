<?php

namespace App\Repositories\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\TipoHabitacion;
use Carbon\Carbon;
use DB;

class TipoHabitacionRepository{

    public function obtenerTipoHabitaciones(){
        $habitaciones=DB::table('gob_tipo_habitacion as th')
            ->select('th.id','th.descripcion')
            ->where('th.estado','=','1')
            ->orderBy('th.id','desc')
            ->get();
        return $habitaciones;
    }

    public function obtenerTipoHabitacionPorId($id){
        return TipoHabitacion::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $tipoHabitacion=null;
        try{
            DB::beginTransaction();

            $tipoHabitacion=new TipoHabitacion($request->all());
            $tipoHabitacion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $tipoHabitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $tipoHabitacion->estado=1;
            $tipoHabitacion->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $tipoHabitacion;
    }

    public function modificarDesdeRequest(Request $request){
        $tipoHabitacion=null;
        try{
            DB::beginTransaction();

            $tipoHabitacion=$this->obtenerTipoHabitacionPorId($request->get('id'));
            $tipoHabitacion->fill($request->all()); //llena datos desde el array entrante en el request.
            $tipoHabitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $tipoHabitacion->update();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $tipoHabitacion;
    }

    public function eliminar($id){

        $tipoHabitacion=$this->obtenerTipoHabitacionPorId($id);
        if ( is_null($clitipoHabitacionente) ){
            App::abort(404);
        }
        $tipoHabitacion->estado='0';
        $tipoHabitacion->update();
        return $tipoHabitacion;
    }

}//fin clase
