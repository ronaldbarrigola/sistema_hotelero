<?php

namespace App\Repositories\Base;

use App\Models\User;
use Illuminate\Http\Request;
use App\Entidades\Base\UsuarioRol;
use App\Repositories\Base\PersonaRepository;
use Illuminate\Support\Facades\Auth;


use Carbon\Carbon;
use DB;

class UsuarioRepository{
    protected $personaRep;
    //===========================================================================================================
    //CONSTRUCTOR
    //===========================================================================================================
    public function __construct(PersonaRepository $personaRep){
        $this->personaRep=$personaRep;
    }

    //=========================================================================================================================
    // OBTENER LISTA DE OBJETOS
    //=========================================================================================================================
    public function obtenerUsuariosDataTables(){
        return datatables()->of(
            DB::table('bas_persona as p')
            ->leftjoin('bas_usuario as u','u.id','=','p.id')
            ->leftjoin('bas_tipo_doc as t','t.id','=','p.tipo_doc_id')
            ->leftjoin('bas_ciudad as c','c.id','=','p.ciudad_exp_id')
            ->leftjoin('bas_sexo as s','s.id','=','p.sexo_id')
            ->select(DB::raw('u.id as "u.id", u.login as "u.login", u.email as "u.email", p.nombre as "p.nombre",p.paterno as "p.paterno" , p.materno as "p.materno", s.nombre as "s.nombre", p.fecha_nac as "p.fecha_nac",
                              t.abreviacion as "t.abreviacion",p.doc_id as "p.doc_id", c.abreviacion as "c.abreviacion",p.email as "p.email",
                              p.telefono as "p.telefono",p.direccion as "p.direccion"'
                              ))
            ->where('p.estado','=','1')
            ->where('u.estado','=','1')
            // ->orderBy('p.id','desc')
        )->toJson();
    }

    public function obtenerListaUsuarios(){
        $usuario=DB::table('bas_usuario as u')
        ->join('bas_persona as p','p.id','=','u.id')
        ->select(DB::raw('u.id,CONCAT(u.id," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) as nombre_completo'))
        ->where('u.estado','=','1')
        ->where('p.estado','=','1')
        ->where('u.agencia_id','=',Auth::user()->agencia_id)
        ->orderBy('p.id','desc')
        ->get();
        return $usuario;
    }

    public function obtenerListaUsuariosAdmin(){  //Lista de usuarios vendedor para el reporte de administrador
        $usuario=DB::table('bas_usuario as u')
        ->join('bas_persona as p','p.id','=','u.id')
        ->select(DB::raw('u.id,CONCAT(u.id," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")," ",IFNULL(p.nombre,"")) as nombre_completo'))
        ->where('u.estado','=','1')
        ->where('p.estado','=','1')
        ->orderBy('p.id','desc')
        ->get();
        return $usuario;
    }

    //=========================================================================================================================
    // OBTENER Todos los Usuarios para combobox
    //=========================================================================================================================
    public function obtenerUsuariosPorCodRol($codigoRol){
        return DB::table('bas_usuario as u')
               ->join('bas_persona as per','per.id','=','u.id')
               ->join('bas_usuario_rol as ur','ur.usuario_id','=','u.id')
               ->join('bas_rol as r','r.id','=','ur.rol_id')
               ->select('u.id','per.nombre','per.paterno')
               ->where('r.codigo','=',$codigoRol)
               ->where('u.estado','=',1)->get();
    }



    //=========================================================================================================================
    // OBTENER correos de usuarios a partir del IDs de roles
    //=========================================================================================================================
    public function obtenerCorreosPorIdsRoles($arrayIds){
        return DB::table('bas_usuario_rol as ur')
               ->leftjoin('bas_usuario as u','u.id','ur.usuario_id')
               ->leftjoin('bas_rol as r','r.id','ur.rol_id')
               ->select('u.email')
               ->whereIn('ur.rol_id',$arrayIds)
               ->where('u.estado','=',1)
               ->distinct()->get();//evita repetir correos para usuarios con mas de un rol
    }


    //=========================================================================================================================
    // OBTENER OBJETO POR ID
    //=========================================================================================================================

    public function obtenerUsuarioPorId($id){
        return User::find($id);
    }


    //=========================================================================================================================
    // INSERTAR MODIFICAR
    //=========================================================================================================================
    public function insertarModificarDesdeRequest(Request $request,$operacion){
        try{
            DB::beginTransaction();

            //1.- GUARDANDO DATOS PERSONA
            $persona=null;
            if($operacion=="insertar"){
                $persona=$this->personaRep->insertarDesdeRequest($request);
            }// fin if insertar
            if($operacion=="modificar"){
                $persona=$this->personaRep->modificarDesdeRequest($request);
            }// fin_if modificar

            // 2.- GUARDANDO USUARIO
            $usuario_id=$persona->id;//En este punto se cuenta con un id de persona ya sea que fue insertado recien o fue modificado.
            $usuario=$this->obtenerUsuarioPorId($usuario_id);
            if($usuario==null){
                $usuario=new User($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
                $usuario->id=$usuario_id;
                //$usuario->fecha_alta=Carbon::now('America/La_Paz')->toDateTimeString();// fecha_alta  para usuario es created_at
                $usuario->password=bcrypt($request->get('login'));
                $usuario->api_token=bcrypt($request->get('login')." ".$request->get('login'));
                $usuario->estado=1;
                $usuario->save();
            }else{
                $usuario->fill($request->all()); //llena datos desde el array entrante en el request.
                $usuario->update();
            }
            // -----------------------------------------------------------------------------------------------
            //3.- GUARDANDO USUARIO ROL
            // -----------------------------------------------------------------------------------------------
            $vec_id=$request->get("vec_id");
            $vec_rol_id=$request->get("vec_rol_id");
            $vec_estado=$request->get("vec_estado");
            //adjuntando roles a usuario. tabla (bas_usuario_rol)
            $limite=0;
            if($vec_id!=null){
                $limite=count($vec_id);
            }
            for($i=0; $i<$limite ; $i++){
                $estado=$vec_estado[$i];
                $idRol=$vec_rol_id[$i];
                // NOTA .---como el id de la tabla usuario no es automatico, no usar $usuario->id para insertar en la tabla usuario Rol--
                if($estado=='nuevo'){
                    $usuarioRol=new UsuarioRol();
                    $usuarioRol->usuario_id=$usuario_id;
                    $usuarioRol->rol_id=$idRol;
                    $usuarioRol->fecha_alta=Carbon::now('America/La_Paz')->toDateTimeString();
                    $usuarioRol->usuario_alta_id=$request->get("usuario_alta_id");
                    $usuarioRol->estado=1;
                    $usuarioRol->save();
                    // $pivote=array('fecha_alta'=>Carbon::now('America/La_Paz')->toDateTimeString(),
                    //                 'usuario_alta_id'=>$usuario_id,
                    //                 'estado'=>1
                    //                 );
                    // $usuario->roles()->attach($idRol,$pivote);//asocia al usuario, el rol con el id especificado y gaurda datos en la tabla de relacion(pivote).
                }
                if($estado=='guardado'){
                    // nada
                }
                if($estado=='eliminado'){
                    $usuario->roles()->detach($idRol);//disocia del usuario el rol con id especificado,
                }
            }
            // -----------------------------------------------------------------------------------------------
            DB::commit();
            return $usuario;
        }catch(\Exception $e){
            DB::rollback();
            //restaurar el id autoincremental al ultimo valor correcto
            $maxId = DB::table('bas_persona')->max('id');
            DB::statement("ALTER TABLE bas_persona AUTO_INCREMENT=$maxId");

            $errors=$e->getMessage();
            //return new JsonResponde($errors,500);
            //return $errors;
            return $errors;
        }
    }

    public function eliminar($id){
        $usuario=$this->obtenerUsuarioPorId($id);
        if ( is_null($usuario) ){
            App::abort(404);
        }
        $usuario->roles()->detach();
        $usuario->delete();
        // $usuario->estado='0';
        // $usuario->update();
        return $usuario;
    }

}//fin clase
