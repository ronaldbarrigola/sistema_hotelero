<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\User;

use App\Repositories\Base\PersonaRepository;
use App\Repositories\Base\TipoDocRepository;
use App\Repositories\Base\CiudadRepository;
use App\Repositories\Base\SexoRepository;
use App\Repositories\Base\EstadoCivilRepository;
use phpDocumentor\Reflection\PseudoTypes\False_;
use Response;

class PersonaController extends Controller
{
    protected $personaRep;
    protected $tipoDocRep;
    protected $ciudadRep;
    protected $sexoRep;
    protected $estadoCivilRep;
    //===========================================================================================================
    //CONSTRUCTOR
    //===========================================================================================================
    public function __construct(PersonaRepository $personaRep,TipoDocRepository $tipoDocRep,CiudadRepository $ciudadRep,SexoRepository $sexoRep,EstadoCivilRepository $estadoCivilRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->personaRep=$personaRep;
        $this->tipoDocRep=$tipoDocRep;
        $this->ciudadRep=$ciudadRep;
        $this->sexoRep=$sexoRep;
        $this->estadoCivilRep=$estadoCivilRep;
    }

    public function index(Request $request){
        if($request->ajax()){
            return $this->personaRep->obtenerPersonasDataTables();
        }else{
            $objetos=Auth::user()->obtenerRolSession()->objetos;
            $col_usuario=$objetos->find(1);// si no encuentra el id 1 correspondiente al objeto columna(AdministrarUsuario) entonces devuelve null
            return view('base.seguridad.persona.index',['col_usuario'=>$col_usuario]);
        }
    }

    public function create_edit($id){
        $persona=$this->personaRep->obtenerPersonaPorId($id);
        $tipo_docs=$this->tipoDocRep->obtenerTipoDocs();
        $ciudades=$this->ciudadRep->obtenerCiudades();
        $sexos=$this->sexoRep->obtenerSexos();
        $estados_civiles=$this->estadoCivilRep->obtenerEstadosCiviles();
        return view('base.seguridad.persona.create_edit',['persona'=>$persona,'tipo_docs'=>$tipo_docs,'ciudades'=>$ciudades,'sexos'=>$sexos,'estados_civiles'=>$estados_civiles]);
    }

    public function store(Request $request){
        $request['usuario_alta_id']=Auth::user()->id;//enviando id de persona logueado
        //$request['predeterminado']=$request->get('predeterminado') === 'on' ? 1 : 0;
        $this->personaRep->insertarDesdeRequest($request);
        return Redirect::to('seguridad/personas');//esto va al index
    }

    public function update(Request $request, $id){
        $request['id']=$id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
        $request['persona_modificacion_id']=Auth::user()->id;//enviando id de persona logueado
        $this->personaRep->modificarDesdeRequest($request);
        return Redirect::to('seguridad/personas');//esto va al index
    }

    public function destroy(Request $request,$id){
        $persona=$this->personaRep->eliminar($id);
        //si la solicitud es ajax no resfresca la pagina solo un <tr>, caso contrario si refresca toda la pagina
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'persona ' . $persona->id. ', eliminado',
                'id'      => $persona->id
            ));
        }
        return Redirect::route('base.seguridad.persona.index');
    }

    public function buscarPorNumDocId(Request $request){
        if($request->ajax()){
            $persona=$this->personaRep->buscarPorNumDocId($request->get('doc_id'));// si no encuentra devuelve null
            return response()->json($persona);
        }
    }

    public function buscarPersonaClientePorDocId(Request $request){
        $response=true;
        $persona=$this->personaRep->buscarPersonaClientePorDocId($request->get('doc_id'));
        if ( is_null($persona) ){
            $response=false;
        }

       return response()->json(array ('persona'=>$persona,'response'=>$response));
    }

} //FIN CLASE
