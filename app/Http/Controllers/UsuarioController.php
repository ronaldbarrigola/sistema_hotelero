<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Base\PersonaRepository;
use App\Repositories\Base\UsuarioRepository;
use App\Repositories\Base\TipoDocRepository;
use App\Repositories\Base\CiudadRepository;
use App\Repositories\Base\SexoRepository;
use App\Repositories\Base\EstadoCivilRepository;
use App\Repositories\Base\SucursalRepository;
use Response;

class UsuarioController extends Controller
{
    protected $personaRep;
    protected $usuarioRep;
    protected $TipoDocRep;
    protected $CiudadRep;
    protected $SexoRep;
    protected $estadoCivilRep;
    protected $sucursalRep;
    //===========================================================================================================
    //CONSTRUCTOR
    //===========================================================================================================
    public function __construct(PersonaRepository $personaRep,UsuarioRepository $usuarioRep,TipoDocRepository $tipoDocRep,CiudadRepository $ciudadRep,SexoRepository $sexoRep,EstadoCivilRepository $estadoCivilRep,SucursalRepository $sucursalRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->personaRep=$personaRep;
        $this->usuarioRep=$usuarioRep;
        $this->tipoDocRep=$tipoDocRep;
        $this->ciudadRep=$ciudadRep;
        $this->sexoRep=$sexoRep;
        $this->estadoCivilRep=$estadoCivilRep;
        $this->sucursalRep=$sucursalRep;
    }
    //===========================================================================================================
    //index (GET)
    //===========================================================================================================
    public function index(Request $request){
        if($request->ajax()){
            return $this->usuarioRep->obtenerUsuariosDataTables();
        }else{
            return view('base.seguridad.usuario.index');
        }
    }

    ///===========================================================================================================
    //formulario para crear o modificar usuario (GET)
    //===========================================================================================================
    public function create_edit($id){
        //dentro de persona esta el usuario si es que fue registrado. caso contrario tendra null en usuario.
        $persona=$this->personaRep->obtenerPersonaPorId($id);

        $tipo_docs=$this->tipoDocRep->obtenerTipoDocs();
        $ciudades=$this->ciudadRep->obtenerCiudades();
        $sexos=$this->sexoRep->obtenerSexos();
        $estados_civiles=$this->estadoCivilRep->obtenerEstadosCiviles();
        $sucursales=$this->sucursalRep->obtenerSucursales();
        //$personas_encar=$this->personaRep->obtenerPersonasSimple();
        //return view('base.seguridad.persona.create_edit',['persona'=>null,'tipo_docs'=>$tipo_docs,'ciudades'=>$ciudades,'sexos'=>$sexos,'estados_civiles'=>$estados_civiles]);
        return view('base.seguridad.usuario.create_edit',['persona'=>$persona,'tipo_docs'=>$tipo_docs,'ciudades'=>$ciudades,'sexos'=>$sexos,'estados_civiles'=>$estados_civiles,'sucursales'=>$sucursales]);
    }

    //===========================================================================================================
    //show (GET)
    //===========================================================================================================
    public function show($id){
        //return response()->json("hola show ".$id);
         //$usuario=$this->usuarioRep->obtenerUsuarioPorId($id);
         return "show";
         //return view('base.seguridad.usuario.show',['usuario'=>$usuario]);
    }


    ///===========================================================================================================
    //CREATE (GET)
    //===========================================================================================================
    // public function create(){
    //     $tipo_docs=$this->tipoDocRep->obtenerTipoDocs();
    //     $ciudades=$this->ciudadRep->obtenerCiudades();
    //     $sexos=$this->sexoRep->obtenerSexos();
    //     return view('base.seguridad.usuario.create',['tipo_docs'=>$tipo_docs,'ciudades'=>$ciudades,'sexos'=>$sexos]);
    // }

    //===========================================================================================================
    //store  (INSERTAR) (POST)
    //===========================================================================================================
    public function store(Request $request){
        $request['usuario_alta_id']=Auth::user()->id;//enviando id de usuario logueado
        $usuario=$this->usuarioRep->insertarModificarDesdeRequest($request,"insertar");
        //return Redirect::to('seguridad/personas');//esto va al index persona
        if(!is_string($usuario)){
            return Redirect::to('seguridad/usuarios');//esto va al index usuario
        }
        echo "Error al guardar";//para ver detalles del error usar -- echo $usuario;
    }

    //===========================================================================================================
    //Edit (GET)
    //===========================================================================================================
    // public function edit($id){
    //     $usuario=$this->usuarioRep->obtenerUsuarioPorId($id);
    //     return view('base.seguridad.usuario.edit',['usuario'=>$usuario,'tipo_docs'=>$tipo_docs,'ciudades'=>$ciudades,'sexos'=>$sexos]);
    // }

    //===========================================================================================================
    //update  (PATCH)(POST)
    //===========================================================================================================
    public function update(Request $request, $id){
        //$request['id']=$id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
        $request['usuario_alta_id']=Auth::user()->id;//enviando id de usuario logueado
        $usuario=$this->usuarioRep->insertarModificarDesdeRequest($request,"modificar");
        //return Redirect::to('seguridad/personas');//esto va al index persona
        if(!is_string($usuario)){
            return Redirect::to('seguridad/usuarios');//esto va al index usuario
        }
        echo "Error al guardar";//para ver detalles del error usar -- echo $usuario;
    }
    //===========================================================================================================
    //destroy (DELETE)(POST) //esto es para eliminar con ajax y refrescar solo la fila que se elimino <tr> ajax
    //===========================================================================================================
    public function destroy(Request $request,$id){
        $usuario=$this->usuarioRep->eliminar($id);
        //si la solicitud es ajax no resfresca la pagina solo un <tr>, caso contrario si refresca toda la pagina
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'usuario ' . $usuario->codigo. ', eliminado',
                'id'      => $usuario->id
            ));
        }
        //return Redirect::route('base.persona.index');
    }

    //===========================================================================================================
    //Edit (GET) MUESTRA VISTA PARA CAMBIO DE PASSWORD
    //===========================================================================================================
    public function editPassword(){
        return view('base.seguridad.usuario.editPassword');
    }

    //===========================================================================================================
    //update  (PATCH)(POST)
    //===========================================================================================================
    public function updatePassword(Request $request){
        $id=$request->get("usuario_id");
        $usuario=$this->usuarioRep->obtenerUsuarioPorId($id);
        //$usuario=Auth::user();//obteniendo el usuario en session
        $usuario->password=bcrypt($request->password);
        $usuario->update();
        if($request->ajax()){
            return response()->json(array (
                'msg'     => 'password reseteado, usuario ' . $usuario->codigo,
                'id'      => $usuario->id
            ));
        }else{
            return Redirect::to('home');//esto va al index
        }
    }



} //FIN CLASE
