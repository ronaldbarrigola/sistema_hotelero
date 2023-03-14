<?php

namespace App\Repositories\Base;

use DB;

class EstadoCivilRepository{
    //=========================================================================================================================
    // OBTENER LISTA DE estados civiles
    //=========================================================================================================================
    public function obtenerEstadosCiviles(){
        $estadosCiviles=DB::table('bas_estado_civil')
        ->where('estado','=','1')
        ->orderBy('id','asc')
        ->get();
        return $estadosCiviles;
    }
    
}
