<?php

namespace App\Repositories\Base;

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

    public function obtenerCiudadesPorPaisId($pais_id){
        $ciudades=DB::table('cli_ciudad as c')
               ->select('c.id','c.descripcion')
               ->where('c.pais_id','=',$pais_id)
               ->where('c.estado','=','1')
               ->orderBy('c.descripcion','asc')
               ->get();
        return $ciudades;
     }

}
