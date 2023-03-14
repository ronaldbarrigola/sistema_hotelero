<?php

namespace App\Repositories\Base;


use App\User;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DB;

class UsuarioRolRepository{
    
    //=========================================================================================================================
    // OBTENER Lista de Roles correspondientes a un idUsuario
    //=========================================================================================================================
    public function obtenerRolesPorIdUsuario($idUsuario){
        return DB::table('bas_usuario_rol as ur')
               ->leftjoin('bas_rol as r','r.id','=','ur.rol_id')
               ->select('ur.id','r.id as rol_id','r.nombre','r.descripcion')
               ->where('ur.usuario_id',$idUsuario)
               ->where('ur.estado','=',1)->get();
    }

    //=========================================================================================================================
    // OBTENER Lista de Roles que no estan asignados al usuario con id igual a idUsuario
    //=========================================================================================================================
    public function obtenerRolesFaltantesPorIdUsuario($idUsuario){
        $arrayIdRoles=DB::table('bas_usuario_rol as ur')
                    ->select('ur.rol_id')
                    ->where('ur.usuario_id',$idUsuario)
                    ->where('ur.estado','=',1)->get()->pluck("rol_id"); //devuelve array de ids de rol
        //return $arrayIdRoles;
        return DB::table('bas_rol as r')
               ->select('r.id as rol_id','r.nombre','r.descripcion')
               ->whereNotIn('r.id',$arrayIdRoles)
               ->where('r.estado','=',1)
               ->get();
    }
 
}//fin clase
