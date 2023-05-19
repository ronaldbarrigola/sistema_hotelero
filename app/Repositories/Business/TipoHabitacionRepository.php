<?php

namespace App\Repositories\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\TipoHabitacion;
use Carbon\Carbon;
use DB;

class TipoHabitacionRepository{

    public function obtenerTipoHabitaciones(){
        $tipo_habitaciones=DB::table('gob_tipo_habitacion as th')
            ->select('th.id','th.codigo','th.descripcion as tipo_habitacion')
            ->where('th.estado','=','1')
            ->orderBy('th.id','desc')
            ->get();
        return $tipo_habitaciones;
    }

    public function obtenerTipoHabitacionesDataTables(){
        $tipo_habitaciones=$this->obtenerTipoHabitaciones();
        return datatables()->of($tipo_habitaciones)->toJson();
    }

    public function obtenerTipoHabitacionPorId($id){
        return TipoHabitacion::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $tipoHabitacion=new TipoHabitacion($request->all());
        $tipoHabitacion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $tipoHabitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $tipoHabitacion->estado=1;
        $tipoHabitacion->save();
        return $tipoHabitacion;
    }

    public function modificarDesdeRequest(Request $request){
        $tipoHabitacion=$this->obtenerTipoHabitacionPorId($request->get('tipo_habitacion_id'));
        $tipoHabitacion->fill($request->all());
        $tipoHabitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $tipoHabitacion->update();
        return  $tipoHabitacion;
    }

    public function eliminar($id){
        $tipoHabitacion=$this->obtenerTipoHabitacionPorId($id);
        if ( is_null($tipoHabitacion) ){
            App::abort(404);
        }
        $tipoHabitacion->delete();
        return $tipoHabitacion;
    }

}//fin clase
