<?php

namespace App\Repositories\Base;

use App\Entidades\Business\Ciudad;
use DB;

class CiudadRepository{
    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerCiudades(){
        $ciudades=DB::table('bas_ciudad')
        ->where('estado','=','1')
        ->orderBy('id','asc')
        ->get();
        return $ciudades;
    }
}
