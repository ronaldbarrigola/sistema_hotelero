<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Motivo;
use Carbon\Carbon;
use DB;

class EstadoReservaRepository{

    public function obtenerEstadoReservas(){
        $estadoReservas=DB::table('res_estado_reserva as r')
        ->select('r.id','r.descripcion as estado_reserva','r.color')
        ->where('r.estado','=','1')
        ->orderBy('r.id','asc')
        ->get();
        return $estadoReservas;
    }
}
