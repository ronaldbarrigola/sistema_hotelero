<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class ServicioRepository{

    public function obtenerServicios(){
        $servicios=DB::table('res_servicio as s')
        ->join('pro_hotel_producto as h','h.id','=','s.id') //Relacion uno a uno entre las tablas res_servicio y pro_hotel_producto
        ->join('pro_producto as p','p.id','=','h.producto_id')
        ->select('s.id','p.descripcion as servicio')
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('s.estado','=','1')
        ->where('h.estado','=','1')
        ->where('p.estado','=','1')
        ->orderBy('s.id','asc')
        ->get();
        return $servicios;
    }
}
