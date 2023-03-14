<?php

namespace App\Repositories\Base;

use DB;

class TipoDocRepository{
    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerTipoDocs(){
        $tipoDocs=DB::table('bas_tipo_doc')
        ->where('estado','=','1')
        ->orderBy('id','asc')
        ->get();
        return $tipoDocs;
    }
    
}
