<?php

namespace App\Repositories\Business;

use Carbon\Carbon;
use DB;

class EmpresaRepository{

    public function obtenerEmpresas(){
       $empresas=DB::table('cli_empresa as e')
              ->select('e.id','e.descripcion')
              ->where('e.estado','=','1')
              ->orderBy('e.descripcion','asc')
              ->get();
       return $empresas;
    }

}//fin clase
