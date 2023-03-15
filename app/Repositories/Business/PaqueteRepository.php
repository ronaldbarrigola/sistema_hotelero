<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Motivo;
use Carbon\Carbon;
use DB;

class PaqueteRepository{

    public function obtenerPaquetes(){
        $paquetes=DB::table('res_paquete as p')
        ->select('p.id','p.descripcion')
        ->where('p.estado','=','1')
        ->orderBy('p.id','asc')
        ->get();
        return $paquetes;
    }
}
