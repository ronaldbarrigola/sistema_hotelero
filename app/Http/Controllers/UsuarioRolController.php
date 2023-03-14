<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Base\UsuarioRolRepository;
use App\User;
use DB;
class UsuarioRolController extends Controller
{
    protected $usuarioRolRep;
    public function __construct(UsuarioRolRepository $usuarioRolRep){
        $this->usuarioRolRep=$usuarioRolRep;
      }

    //===========================================================================================================
    //retornar Roles por idUsuario
    //===========================================================================================================
     public function obtenerRolesPorIdUsuario(Request $request){
         $id=$request->get("usuarioId");
        //if($request->ajax()){
            $listaRoles=$this->usuarioRolRep->obtenerRolesPorIdUsuario($id);
            return response()->json($listaRoles);
        //}
    }

     //===========================================================================================================
    //lista de roles que no son del usuario cuyo id
    //===========================================================================================================
    public function obtenerRolesFaltantesPorIdUsuario(Request $request){
        $id=$request->get("usuarioId");
       //if($request->ajax()){
           $listaRoles=$this->usuarioRolRep->obtenerRolesFaltantesPorIdUsuario($id);
           return response()->json($listaRoles);
       //}
   }
    //===========================================================================================================

}
