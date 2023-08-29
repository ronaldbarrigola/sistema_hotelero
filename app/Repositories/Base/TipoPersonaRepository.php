<?php

namespace App\Repositories\Base;

use DB;

class TipoPersonaRepository{
    public function obtenerTipoPersona(){
        $tipoPersona=DB::table('bas_tipo_persona')
        ->where('estado','=','1')
        ->orderBy('id','desc')
        ->get();
        return $tipoPersona;
    }
}
