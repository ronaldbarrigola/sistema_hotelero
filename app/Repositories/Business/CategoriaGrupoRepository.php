<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use App\Entidades\Business\CategoriaGrupo;
use Carbon\Carbon;
use DB;

class CategoriaGrupoRepository{
    public function obtenerGrupos(){
        $grupos=DB::table('pro_grupo as g')
        ->select('g.id','g.grupo')
        ->where('g.estado','=','1')
        ->orderBy('g.id','asc')
        ->get();
        return $grupos;
    }
}
