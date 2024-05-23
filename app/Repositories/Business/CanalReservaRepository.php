<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class CanalReservaRepository{

    public function obtenerCanalReserva(){
        $canal_reserva=DB::table('res_canal_reserva as c')
        ->select('c.id','c.nombre')
        ->where('c.estado','=','1')
        ->orderBy('c.id','asc')
        ->get();
        return $canal_reserva;
    }
}
