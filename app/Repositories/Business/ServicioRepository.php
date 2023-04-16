<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ServicioRepository{

    public function obtenerServicios(){
        $servicios=DB::table('res_servicio as s')
        ->select('s.id','s.descripcion as servicio','s.hora_ini','s.hora_fin')
        ->where('s.estado','=','1')
        ->orderBy('s.id','asc')
        ->get();
        return $servicios;
    }
}
