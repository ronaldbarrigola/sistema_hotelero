<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\TipoTransaccion;
use Carbon\Carbon;
use DB;

class TipoTransaccionRepository{

    public function obtenerTipoTransacciones(){
        $tipoTranacciones=DB::table('con_tipo_transaccion as t')
        ->select('t.id','t.descripcion')
        ->where('t.estado','=','1')
        ->orderBy('t.id','asc')
        ->get();
        return $tipoTranacciones;
    }


    public function factorReservaPorId($id){
        return TipoTransaccion::find($id)->factor;
     }


}
