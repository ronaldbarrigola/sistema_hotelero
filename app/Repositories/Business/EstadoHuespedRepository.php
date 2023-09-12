<?php

namespace App\Repositories\Business;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class EstadoHuespedRepository{

    public function obtenerEstadoHuesped(){
        $estado_huesped=DB::table('res_estado_huesped as e')
            ->select('e.id','e.descripcion as estado_huesped')
            ->where('e.estado','=','1')
            ->orderBy('e.id','asc')
            ->get();
        return $estado_huesped;
    }
}//fin clase
