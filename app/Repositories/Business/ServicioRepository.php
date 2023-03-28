<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Motivo;
use Carbon\Carbon;
use DB;

class ServicioRepository{

    public function obtenerServicios(){
        $servicios=DB::table('res_servicio as s')
        ->join('pro_producto as p','p.id','=','s.id') //Relacion uno a uno entre las tablas res_servicio y pro_producto
        ->select('s.id','p.descripcion as servicio')
        ->where('s.estado','=','1')
        ->where('p.estado','=','1')
        ->orderBy('s.id','asc')
        ->get();
        return $servicios;
    }
}
