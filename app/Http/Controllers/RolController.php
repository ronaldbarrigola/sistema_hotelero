<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Base\RolRepository;
use App\User;
use DB;

class RolController extends Controller
{
    protected $rolRep;

    public function __construct(RolRepository $rolRep){
        $this->middleware('auth');
        $this->middleware('guest',['except' => ['seleccionarRol','ingresarSistema']]);
        $this->rolRep=$rolRep;
    }

    //===========================================================================================================
    //Seleccionar Rol
    //===========================================================================================================
    public function seleccionarRol(Request $request){
        $usuario=Auth::user();//usuario autenticado
        $cantidadRoles=$usuario->roles->count();
        if($cantidadRoles<=0){
            Auth::logout();
            return Redirect::back()->withDanger( 'No tiene Rol asignado!!!' );
        }
        if($cantidadRoles==1){
            $rol=Auth::user()->roles()->first();
            session()->put("ROL_ID",$rol->id);
            //return Redirect::route('home', ['idrol' => $rol->id]);
            return Redirect::to('home');
        }

        if($cantidadRoles>=2){
            //pagina de seleccion de roles
            return view('base.seguridad.roles.selrol');
        }
    }

    //===========================================================================================================
    //Ingresar al Sistema
    //===========================================================================================================
    public function ingresarSistema(Request $request){
        session()->put("ROL_ID",$request->idrol);
        //return Redirect::route('home');
        return Redirect::to('home');
    }

    //===========================================================================================================


    // ########################################################################################################

    //===========================================================================================================
    //index (GET)
    //===========================================================================================================
    public function index(Request $request){
        if($request->ajax()){
            return $this->rolRep->obtenerRolesDataTables();
        }else{
            return view('base.seguridad.roles.index');
        }
    }

    //===========================================================================================================
    //store  (INSERTAR) (POST)
    //===========================================================================================================
    public function store(Request $request){
        if($request->ajax()){
            //$idUsuario=Auth::user()->id;
            // VERIFICANDO SI EXISTIA UN ROL DADO DE BAJA. SI ES ASI SOLO HABRIA QUE ACTUALIZAR EL ESTADO Y NUEVOS DATOS Y NO INSERTAR
            $rol=$this->rolRep->obtenerRolPorCodigoEliminado($request->get("codigo"));
            if($rol!=null){
                $request['id']=$rol->id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
                $request['estado']=1;
                $rol=$this->rolRep->modificarDesdeRequest($request);
            }
            else{//insertar.
                $rol= $this->rolRep->insertarDesdeRequest($request);
            }
            return response()->json($rol);
        }
    }

    //===========================================================================================================
    //show (GET)
    //===========================================================================================================
    public function show($id){
    }
    //===========================================================================================================
    //Edit (GET)
    //===========================================================================================================
    public function edit(Request $request,$id){
        if($request->ajax()){
            $rol=$this->rolRep->obtenerRolPorId($id);
            return response()->json($rol);
        }
    }


    //===========================================================================================================
    //update  (PATCH)(POST)
    //===========================================================================================================
    public function update(Request $request, $id){
        if($request->ajax()){
            $request['id']=$id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
            $rol=$this->rolRep->modificarDesdeRequest($request);
            return response()->json($rol);
        }
    }

    //===========================================================================================================
    //destroy (DELETE)(POST) //esto es para eliminar con ajax y refrescar solo la fila que se elimino <tr> ajax
    //===========================================================================================================
    public function destroy(Request $request,$id){
        $rol=$this->rolRep->eliminar($id);
        if($request->ajax()){
            return response()->json(array (
                'msg'     => 'Rol ' . $rol->id . ', eliminado',
                'id'      => $rol->id
            ));
        }
    }
    //===========================================================================================================

}
