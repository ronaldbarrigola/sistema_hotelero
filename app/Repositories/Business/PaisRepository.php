<?php

namespace App\Repositories\Business;

use Carbon\Carbon;
use DB;

class PaisRepository{

    public function obtenerPaises(){
       $paises=DB::table('cli_pais as p')
              ->select('p.id','p.descripcion')
              ->where('p.estado','=','1')
              ->orderBy('p.descripcion','asc')
              ->get();
       return $paises;
    }

}//fin clase
