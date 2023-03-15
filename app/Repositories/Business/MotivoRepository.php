<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Motivo;
use Carbon\Carbon;
use DB;

class MotivoRepository{

    public function obtenerMotivos(){
        $motivos=DB::table('res_motivo as m')
        ->select('m.id','m.descripcion')
        ->where('m.estado','=','1')
        ->orderBy('m.id','asc')
        ->get();
        return $motivos;
    }
}
