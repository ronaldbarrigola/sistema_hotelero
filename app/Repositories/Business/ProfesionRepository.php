<?php

namespace App\Repositories\Business;

use Carbon\Carbon;
use DB;

class ProfesionRepository{

    public function obtenerProfesiones(){
       $profesiones=DB::table('cli_profesion as p')
              ->select('p.id','p.descripcion')
              ->where('p.estado','=','1')
              ->orderBy('p.descripcion','asc')
              ->get();
       return $profesiones;
    }

}//fin clase
