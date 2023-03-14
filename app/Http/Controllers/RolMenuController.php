<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Base\RolMenuRepository;

class RolMenuController extends Controller
{
    protected $rolMenuRep;
    //===constructor=============================================================================================
    public function __construct(RolMenuRepository $rolMenuRep){
         $this->middleware('auth');
         $this->middleware('guest');
        $this->rolMenuRep=$rolMenuRep;
    }
    //===========================================================================================================
    // listado para asignacion de  menus para rol
    //===========================================================================================================
    public function asignacionMenusPorIdRol(Request $request){
        if($request->ajax()){
            return $this->rolMenuRep->asignacionMenusPorIdRol($request->get("idRol"));
        }
    }

    //===========================================================================================================
    // Guardar asginacion de menus
    //===========================================================================================================
    public function guardarAsignacionMenus(Request $request){
        if($request->ajax()){
            $idUsuario=Auth::user()->id;
            $request['usuario_alta_id']=$idUsuario;//enviando id de usuario logueado
             $rol= $this->rolMenuRep->insertarDesdeRequest($request);
            return response()->json($rol);
        }
        //return Redirect::to('venta/pedidos');//esto va al index

    }

    //===========================================================================================================
    // listado de menus para navbar principal
    //===========================================================================================================
    public function listaMenusPorIdRol(Request $request){
        if($request->ajax()){
            return $this->rolMenuRep->listaMenusPorIdRol($request->get("idRol"));
        }

    }

    //================================================================================================
}//fin clase
