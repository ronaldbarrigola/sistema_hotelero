<?php

namespace App\Repositories\Base;

use DB;

class SexoRepository{
    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerSexos(){
        $sexos=DB::table('bas_sexo')
        ->where('estado','=','1')
        ->orderBy('id','asc')
        ->get();
        return $sexos;
    }
    
}
