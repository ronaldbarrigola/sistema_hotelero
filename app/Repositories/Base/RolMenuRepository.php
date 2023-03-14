<?php

namespace App\Repositories\Base;
use Illuminate\Http\Request;
use App\Entidades\Base\RolMenu;
use App\Entidades\Base\Rol;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use DB;

class RolMenuRepository{

    // JsonResponde($errors,500)
    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function asignacionMenusPorIdRol($idRol){
            // use ($idRol) usa parametro externo dentro de funcion en leftjoin
            return DB::table('bas_menu as m')
            ->leftjoin('bas_rol_menu as rm', function ($leftjoin) use ($idRol) {
                $leftjoin->on('m.id', '=', 'rm.menu_id')
                     ->where('rm.rol_id', '=', $idRol)
                     ->where('rm.estado', '=', 1);
            })
            ->select('m.id','m.nombre','m.padre_id','m.orden','m.icono','m.url',
                     DB::raw('(CASE WHEN rm.rol_id is NULL THEN 0 ELSE 1 END) AS asignado')
                    )
            ->where('m.estado','=','1')
            ->orderBy('m.id','asc')
            ->get();
    }

    //=========================================================================================================================
    // MENUS PARA NAVBAR, MENU PRINCIPAL
    //=========================================================================================================================
    public function listaMenusPorIdRol($idRol){
        // use ($idRol) usa parametro externo dentro de funcion en leftjoin
        return DB::table('bas_menu as m')
        ->leftjoin('bas_rol_menu as rm', function ($leftjoin) use ($idRol) {
            $leftjoin->on('m.id', '=', 'rm.menu_id')
                 ->where('rm.estado', '=', 1);
        })
        ->select('m.id','m.nombre','m.padre_id','m.orden','m.icono','m.url',
                 DB::raw('(CASE WHEN rm.rol_id is NULL THEN 0 ELSE 1 END) AS asignado')
                )
        ->where('m.estado','=','1')
        ->where('rm.rol_id', '=', $idRol)
        ->orderBy('m.orden','asc')
        ->get();
}

    //=========================================================================================================================
    // OBTENER OBJETO POR DOS FILTROS
    //=========================================================================================================================
    private function obtenerRolMenuPorIdRoleIdMenu($idRol,$idMenu){
        return ROlMenu::where('rol_id','=',$idRol)
        ->where('menu_id','=',$idMenu)
        ->first();
    }

    //=========================================================================================================================
    // OBTENER OBJETO POR ID
    //=========================================================================================================================
    // public function obtenerRolMenuPorId($id){
    //     return RolMenu::find($id);
    // }

    //=========================================================================================================================
    // INSERTAR
    //=========================================================================================================================
    public function insertarDesdeRequest(Request $request){
        $rolMenu="";
        try{
            DB::beginTransaction();

            //$rolMenu=new RolMenu($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
            //$objeto=json_decode($request->getContent(), true);
            //$objeto=$request->json()->all();
            $IdRol=$request->get("rol_id");
            $vec_asignado=$request->get("vec_asignado");
            $vec_menu_id=$request->get("vec_menu_id");
            //cambiando roles para menu

            for($i=0; $i<count($vec_menu_id) ; $i++){
                $menuId=$vec_menu_id[$i];
                if($menuId=='null'){continue;}
                $rolMenu=$this->obtenerRolMenuPorIdRoleIdMenu($IdRol,$menuId);
                $asignado=intval($vec_asignado[$i]);
                if($rolMenu==null){
                    if($asignado==1){ // si no existe y esta marcado registrar.
                        //no existe, hay que insertar
                        $rolMenu=new RolMenu();
                        $rolMenu->rol_id=$IdRol;
                        $rolMenu->menu_id=$menuId;
                        $rolMenu->usuario_alta_id=$request->get("usuario_alta_id");
                        $rolMenu->estado=1;
                        $rolMenu->fecha_alta=Carbon::now('America/La_Paz')->toDateTimeString();
                        $rolMenu->save();
                    }
                }else{
                    //$rolMenu=$this->obtenerRolMenuPorId($rolMenu->id);
                    if($rolMenu->estado!=$asignado)
                    $rolMenu->estado=$asignado;
                    $rolMenu->update();
                }
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            $errors=$e->getMessage();
            //return response()->json($rolMenuAditamento);
            return new JsonResponse($errors, 500);
        }
        return Rol::find($IdRol);
    }
    //=========================================================================================================================
}
