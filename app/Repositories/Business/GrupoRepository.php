<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Grupo;
use App\Entidades\Business\Reserva;
use Carbon\Carbon;
use DB;

class GrupoRepository{

    public function obtenerGrupos(){
        $grupos=DB::table('res_grupo as g')
        ->select('g.id','g.fecha','g.nombre')
        ->where('g.estado','=','1')
        ->orderBy('g.id','asc')
        ->get();
        return $grupos;
    }

    public function obtenerGruposDataTables(){
        $grupos=$this->obtenerGrupos();
        return datatables()->of($grupos)->toJson();
    }

    public function obtenerGruposPorReservaId(Request $request){
        $accion="nuevo";
        $selected_items = $request->input('selected_items');

        $grupo_id=-1;
        foreach($selected_items as $reserva_id) {
            $grupo_id=$this->obtenerGrupoIdPorReservaId($reserva_id);
            $grupo=Grupo::find($grupo_id);
            if(!is_null($grupo)){
                $accion="modificar";
                break;
            } else {
                $grupo=new Grupo();
            }
         }

        $reservas=DB::table('res_reserva as r')
        ->leftjoin('res_grupo as g','g.id','=','r.grupo_id')
        ->leftjoin('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->select(DB::raw('IFNULL(g.id,"") as grupo_id'),'r.id as reserva_id',DB::raw('IFNULL(g.nombre,"") as nombre'),'h.num_habitacion',DB::raw('CASE WHEN g.id IS NULL THEN "nuevo" ELSE "guardado" END as estado'))
        ->where(function ($query) use ($selected_items, $grupo_id) {
            $query->whereIn('r.id',$selected_items)
                  ->orWhere('r.grupo_id', '=', $grupo_id);
        })
        ->where('r.estado','=','1')
        ->orderBy('g.id','asc')
        ->get();

        return response()->json(['grupo'=>$grupo,'reservas'=>$reservas,'accion'=>$accion]);
    }

    public function obtenerGrupoIdPorReservaId($reserva_id){
        $grupo_id = -1;
        $reserva = Reserva::find($reserva_id);
        if (!is_null($reserva)) {
            $grupo_id = !is_null($reserva->grupo_id) ? $reserva->grupo_id : -1;
        }
        return $grupo_id;
    }

    public function obtenerGrupoPorId($id){
        return Grupo::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $nombre=$request['nombre_grupo'];
        $color=$request['color_grupo'];
        $grupo=new Grupo();
        $grupo->nombre=$nombre;
        $grupo->color=$color;
        $grupo->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $grupo->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $grupo->estado=1;
        $grupo->save();
        return $grupo;
    }

    public function insertarGrupoReserva(Request $request)
    {
        $response = true;
        $message = "";
        $lista_reserva=[];
        try {
            DB::beginTransaction();
            $grupo = $this->insertarDesdeRequest($request);
            if ($grupo) {
                $vec_reserva = $request->input('vec_reserva', []);
                $vec_estado = $request->input('vec_estado', []);
                $index=0;
                foreach ($vec_reserva as $id) {
                    $estado=$vec_estado[$index];

                    if($estado=='nuevo'){
                        $reserva = Reserva::find($id);
                        if ($reserva) {
                            $reserva->grupo_id = $grupo->id;
                            $reserva->update();
                        } else {
                            $response = false;
                            $message = "La reserva nro: $id no existe.";
                            break;
                        }
                    }

                    if($estado=='guardado'){
                        //Sin acciones
                    }

                    if($estado=='eliminado'){
                        $reserva = Reserva::find($id);
                        $reserva->grupo_id=null;
                        $reserva->update();
                    }

                    $lista_reserva[$index]=$id;
                    $index++;
                }

                if($response){
                    DB::commit();
                } else {
                    DB::rollback();
                }

            } else {
                $response = false;
                $message = "No se pudo insertar el grupo.";
                DB::rollback();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = false;
            $message = "Error al ejecutar la operación, consulte con el administrador del sistema";
        }

        return response()->json(['response' => $response,'message' => $message,'lista_reserva' => $lista_reserva]);
    }

    public function modificarGrupoReserva(Request $request){
        try {
            DB::beginTransaction();

            $response = true;
            $message = "";
            $lista_reserva=[];

            $grupo_id=$request['id'];
            $nombre=$request['nombre_grupo'];
            $color=$request['color_grupo'];
            $grupo=$this->obtenerGrupoPorId($grupo_id);
            $grupo->nombre= $nombre;
            $grupo->color= $color;
            $grupo->update();

            if($grupo){
                $vec_reserva = $request->input('vec_reserva', []);
                $vec_estado = $request->input('vec_estado', []);
                $index=0;
                foreach ($vec_reserva as $id) {
                    $estado=$vec_estado[$index];

                    if($estado=='nuevo'){
                        $reserva = Reserva::find($id);
                        if ($reserva) {
                            $reserva->grupo_id = $grupo->id;
                            $reserva->update();
                        } else {
                            $response = false;
                            $message = "La reserva nro: $id no existe.";
                            break;
                        }
                    }

                    if($estado=='guardado'){
                       //Sin acciones
                    }

                    if($estado=='eliminado'){
                        $reserva = Reserva::find($id);
                        $reserva->grupo_id=null;
                        $reserva->update();
                    }

                    $lista_reserva[$index]=$id;
                    $index++;
                }

                if($response){
                    DB::commit();
                } else {
                    DB::rollback();
                }

            } else {
                $response = false;
                $message = "No se pudo modificar el grupo.";
                DB::rollback();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = false;
            $message = "Error al modificar la operación, consulte con el administrador del sistema";
        }

        return response()->json(['response' => $response,'message' => $message,'lista_reserva' => $lista_reserva]);
    }

    public function eliminar($id){
        $grupo=$this->obtenerGrupoPorId($id);
        $grupo->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $grupo->estado='0';
        $grupo->update();
        return $grupo;
    }
}
