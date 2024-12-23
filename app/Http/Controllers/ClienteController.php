<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Base\PersonaRepository;
use App\Repositories\Base\TipoPersonaRepository;
use App\Repositories\Base\TipoDocRepository;
use App\Repositories\Business\ClienteCiudadRepository;
use App\Repositories\Base\SexoRepository;
use App\Repositories\Base\EstadoCivilRepository;
use App\Repositories\Business\ClienteRepository;
use App\Repositories\Business\PaisRepository;
use App\Repositories\Business\ProfesionRepository;
use App\Repositories\Business\EmpresaRepository;

class ClienteController extends Controller
{
    protected $personaRep;
    protected $tipoPersonaRep;
    protected $tipoDocRep;
    protected $paisRep;
    protected $ciudadRep;
    protected $profesionRep;
    protected $empresaRep;
    protected $sexoRep;
    protected $estadoCivilRep;
    protected $clienteRep;

    //===constructor=============================================================================================
    public function __construct(ClienteRepository $clienteRep,TipoPersonaRepository $tipoPersonaRep,PersonaRepository $personaRep,TipoDocRepository $tipoDocRep,PaisRepository $paisRep,ClienteCiudadRepository $ciudadRep,ProfesionRepository $profesionRep,EmpresaRepository $empresaRep,SexoRepository $sexoRep,EstadoCivilRepository $estadoCivilRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->personaRep=$personaRep;
        $this->tipoPersonaRep=$tipoPersonaRep;
        $this->tipoDocRep=$tipoDocRep;
        $this->paisRep=$paisRep;
        $this->ciudadRep=$ciudadRep;
        $this->profesionRep=$profesionRep;
        $this->empresaRep=$empresaRep;
        $this->ciudadRep=$ciudadRep;
        $this->sexoRep=$sexoRep;
        $this->estadoCivilRep=$estadoCivilRep;
        $this->clienteRep=$clienteRep;
    }

     //===========================================================================================================
     public function index(Request $request){
        if($request->ajax()){
            return $this->clienteRep->obtenerClientesDataTables();
        }else{
            return view('business.cliente.index');
        }
    }

    public function create(){
        $tipo_persona=$this->tipoPersonaRep->obtenerTipoPersona();
        $tipo_docs=$this->tipoDocRep->obtenerTipoDocs();
        $paises=$this->paisRep->obtenerPaises();
        $ciudades=$this->ciudadRep->obtenerCiudades();
        $profesiones=$this->profesionRep->obtenerProfesiones();
        $empresas=$this->empresaRep->obtenerEmpresas();
        $sexos=$this->sexoRep->obtenerSexos();
        $estados_civiles=$this->estadoCivilRep->obtenerEstadosCiviles();
        return response()->json(array ('tipo_docs'=>$tipo_docs,'tipo_persona'=>$tipo_persona,'paises'=>$paises,'ciudades'=>$ciudades,'profesiones'=>$profesiones,'empresas'=>$empresas,'sexos'=>$sexos,'estados_civiles'=>$estados_civiles));
    }

    public function store(Request $request){
        $cliente=$this->clienteRep->insertarDesdeRequest($request);
        $clientes=$this->clienteRep->obtenerClientes();
        $persona=$cliente->persona; //Cargar entidad relacion 1 a 1
        $tipo_documento=$persona->tipo_documento; //Cargar entidad relacion 1 a N inversa
        return response()->json(array ('clientes'=>$clientes,'cliente'=>$cliente,'persona'=>$persona,'tipo_documento'=>$tipo_documento));
    }

    public function edit(Request $request){
        $id=$request['persona_id'];//El mismo id se usa mapra persona y cliente
        $persona=$this->personaRep->obtenerPersonaPorId($id);
        $cliente=$this->clienteRep->obtenerClientePorId($id);

        $tipo_persona=$this->tipoPersonaRep->obtenerTipoPersona();
        $tipo_docs=$this->tipoDocRep->obtenerTipoDocs();
        $paises=$this->paisRep->obtenerPaises();
        $profesiones=$this->profesionRep->obtenerProfesiones();
        $empresas=$this->empresaRep->obtenerEmpresas();
        $sexos=$this->sexoRep->obtenerSexos();
        $estados_civiles=$this->estadoCivilRep->obtenerEstadosCiviles();

        $ciudades=null;
        if( $cliente!=null){
            $ciudades=$this->ciudadRep->obtenerCiudadesPorPaisId($cliente->pais_id);
        }

        return response()->json(array ('persona'=>$persona,'tipo_persona'=>$tipo_persona,'cliente'=>$cliente,'tipo_docs'=>$tipo_docs,'paises'=>$paises,'ciudades'=>$ciudades,'profesiones'=>$profesiones,'empresas'=>$empresas,'sexos'=>$sexos,'estados_civiles'=>$estados_civiles));
    }

    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);//El mismo id se usa mapra persona y cliente
        $cliente=$this->clienteRep->modificarDesdeRequest($request);
        $clientes=$this->clienteRep->obtenerClientes();
        $persona=$cliente->persona; //Cargar entidad relacion 1 a 1
        $tipo_documento=$persona->tipo_documento; //Cargar entidad relacion 1 a N inversa
        return response()->json(array ('clientes'=>$clientes,'cliente'=>$cliente,'persona'=>$persona,'tipo_documento'=>$tipo_documento));
    }

    public function destroy(Request $request,$id){
        $cliente=$this->clienteRep->eliminar($id);

        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'cliente ' . $cliente->cliente. ', eliminada',
                'id'      => $cliente->id
            ));
        }

        return Redirect::route('business.cliente.index');
    }

    function obtenerClientes(){
        $clientes=$this->clienteRep->obtenerClientes();
        return response()->json(array ('clientes'=>$clientes));
    }

}
