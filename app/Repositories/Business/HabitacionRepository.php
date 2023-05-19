<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Habitacion;
use Carbon\Carbon;
use DB;

class HabitacionRepository{


    public function obtenerHabitaciones(){
       $habitaciones=DB::table('gob_habitacion as h')
            ->leftjoin('gob_tipo_habitacion as th','th.id','=','h.tipo_habitacion_id')
            ->leftjoin('gob_estado_habitacion as eh','eh.id','=','h.estado_habitacion_id')
            ->leftjoin('bas_agencia as a','a.id','=','h.agencia_id')
            ->select('h.id','h.descripcion as habitacion','h.num_habitacion','h.piso','a.nombre as agencia','th.descripcion as tipo_habitacion','eh.descripcion as estado_habitacion',DB::raw('IFNULL(h.precio,0) as precio'),'h.imagen')
            ->where('h.agencia_id','=',Auth::user()->agencia_id)
            ->where('h.estado','=','1')
            ->orderBy('h.id','desc')
            ->get();
        return $habitaciones;
    }

    public function obtenerHabitacionesDataTables(){
        $habitaciones=$this->obtenerHabitaciones();
        return datatables()->of($habitaciones)->toJson();
    }

    public function obtenerHabitacionPorId($id){
       return Habitacion::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $habitacion=null;
        try{
            DB::beginTransaction();

            $habitacion=new Habitacion($request->all());
            $habitacion->agencia_id=Auth::user()->agencia_id;
            $habitacion->usuario_alta_id=Auth::user()->id;
            $habitacion->usuario_modif_id=Auth::user()->id;
            $habitacion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $habitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $habitacion->estado=1;
            $habitacion->save();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $habitacion;
    }

    public function modificarDesdeRequest(Request $request){
        $habitacion=null;
        try{
            DB::beginTransaction();

            $habitacion=$this->obtenerHabitacionPorId($request->get('id'));
            $habitacion->fill($request->all());
            $habitacion->usuario_modif_id=Auth::user()->id;
            $habitacion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $habitacion->update();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $habitacion;
    }

    public function eliminar($id){
        $habitacion=$this->obtenerHabitacionPorId($id);
        if ( is_null($habitacion) ){
            App::abort(404);
        }
        $habitacion->estado='0';
        $habitacion->update();
        return $habitacion;
    }

}//fin clase
