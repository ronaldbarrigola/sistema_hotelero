<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use DB;

class FormaPagoRepository{

    public function obtenerFormaPagos(){
        $formaPagos=DB::table('con_forma_pago as f')
        ->select('f.id','f.descripcion')
        ->where('f.estado','=','1')
        ->orderBy('f.orden','asc')
        ->get();
        return $formaPagos;
    }
}
