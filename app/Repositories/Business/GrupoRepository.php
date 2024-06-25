<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Grupo;
use App\Repositories\Business\ReservaRepository;
use App\Entidades\Business\Reserva;
use Carbon\Carbon;
use DB;

class GrupoRepository{

    protected $reservaRep;

     //===constructor=============================================================================================
     public function __construct(ReservaRepository $reservaRep){
        $this->reservaRep=$reservaRep;
    }

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
        $response = false;
        $message = "Error al ejecutar la operación, consulte con el administrador del sistema";
        $lista_reserva=[];
        try {
            DB::beginTransaction();
            $grupo = $this->insertarDesdeRequest($request);
            if ($grupo) {
                $vec_reserva = $request->input('vec_reserva', []);
                $reservasActualizadas = 0;
                $index=0;
                foreach ($vec_reserva as $id) {
                    $reserva = $this->reservaRep->obtenerReservaPorId($id);
                    if ($reserva) {
                        $reserva->grupo_id = $grupo->id;
                        $reserva->update();
                        $reservasActualizadas++;
                    } else {
                        $response = false;
                        $message = "La reserva nro: $id no existe.";
                        break;
                    }

                    $lista_reserva[$index]=$id;
                    $index++;
                }

                // Verificar si todas las reservas fueron actualizadas correctamente
                if ($reservasActualizadas === count($vec_reserva)) {
                    $response = true;
                    $message = "Grupo y reservas insertados correctamente.";
                    DB::commit();
                } else {
                    DB::rollback();
                }
            } else {
                $message = "No se pudo insertar el grupo.";
                DB::rollback();
            }
        } catch (\Exception $e) {
            DB::rollback();
        }

        return response()->json(['response' => $response,'message' => $message,'lista_reserva' => $lista_reserva]);
    }

    public function modificarDesdeRequest(Request $request){
        $grupo=$this->obtenerGrupoPorId($request->get('id'));
        $grupo->fill($request->all()); //llena datos desde el array entrante en el request.
        $grupo->update();
        return $grupo;
    }

    public function eliminar($id){
        $grupo=$this->obtenerGrupoPorId($id);
        $grupo->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $grupo->estado='0';
        $grupo->update();
        return $grupo;
    }
}
