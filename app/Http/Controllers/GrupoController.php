<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\GrupoRepository;

class GrupoController extends Controller
{
    protected $grupoRep;

    public function __construct(GrupoRepository $grupoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->grupoRep=$grupoRep;
    }

    public function store(Request $request){
        return $this->grupoRep->insertarGrupoReserva($request);
    }

    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $habitacion=$this->grupoRep->modificarGrupoReserva($request);
        return  $habitacion;
    }

    public function obtenerGruposPorReservaId(Request $request){
        return $this->grupoRep->obtenerGruposPorReservaId($request);
    }

}
