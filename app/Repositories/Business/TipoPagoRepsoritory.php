<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\Motivo;
use Carbon\Carbon;
use DB;

class MotivoRepository{

    public function obtenerTipoPagos(){
        $tipoPagos=DB::table('con_tipo_pago as t')
        ->select('t.id','t.descripcion')
        ->where('t.estado','=','1')
        ->orderBy('t.id','asc')
        ->get();
        return $tipoPagos;
    }
}
