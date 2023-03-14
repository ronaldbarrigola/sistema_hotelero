<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Base\PersonaRepository;
use App\Repositories\Base\TipoDocRepository;
use App\Repositories\Base\CiudadRepository;
use App\Repositories\Base\SexoRepository;
use App\Repositories\Base\EstadoCivilRepository;
use App\Repositories\Business\ClienteRepository;
use App\Repositories\Business\PaisRepository;
use App\Repositories\Business\ProfesionRepository;
use App\Repositories\Business\EmpresaRepository;
use Carbon\Carbon;

class ClienteController extends Controller
{
    protected $personaRep;
    protected $tipoDocRep;
    protected $paisRep;
    protected $ciudadRep;
    protected $profesionRep;
    protected $empresaRep;
    protected $sexoRep;
    protected $estadoCivilRep;
    protected $clienteRep;

    //===constructor=============================================================================================
    public function __construct(ClienteRepository $clienteRep,PersonaRepository $personaRep,TipoDocRepository $tipoDocRep,PaisRepository $paisRep,CiudadRepository $ciudadRep,ProfesionRepository $profesionRep,EmpresaRepository $empresaRep,SexoRepository $sexoRep,EstadoCivilRepository $estadoCivilRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->personaRep=$personaRep;
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
            $id="";
            $persona=$this->personaRep->obtenerPersonaPorId($id);
            $tipo_docs=$this->tipoDocRep->obtenerTipoDocs();
            $paises=$this->paisRep->obtenerPaises();
            $ciudades=$this->ciudadRep->obtenerCiudades();
            $profesiones=$this->profesionRep->obtenerProfesiones();
            $empresas=$this->empresaRep->obtenerEmpresas();
            $sexos=$this->sexoRep->obtenerSexos();
            $estados_civiles=$this->estadoCivilRep->obtenerEstadosCiviles();
            return view('business.cliente.index',['persona'=>$persona,'tipo_docs'=>$tipo_docs,'paises'=>$paises,'ciudades'=>$ciudades,'profesiones'=>$profesiones,'empresas'=>$empresas,'sexos'=>$sexos,'estados_civiles'=>$estados_civiles]);
        }
    }

    //================================================================================================
    public function store(Request $request){
        $cliente=$this->clienteRep->insertarDesdeRequest($request);
        return response()->json(array ('cliente'=>$cliente));
    }

    //================================================================================================
    public function edit(Request $request){
        $id=$request['cliente_id'];//El mismo id se usa mapra persona y cliente
        $persona=$this->personaRep->obtenerPersonaPorId($id);
        $cliente=$this->clienteRep->obtenerClientePorId($id);

        $ciudades=null;
        if( $cliente!=null){
            $ciudades=$this->ciudadRep->obtenerCiudadesPorPaisId($cliente->pais_id);
        }

        return response()->json(array ('persona'=>$persona,'cliente'=>$cliente,'ciudades'=>$ciudades));
    }

    //================================================================================================
    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);//El mismo id se usa mapra persona y cliente
        $cliente=$this->clienteRep->modificarDesdeRequest($request);
        return  $cliente;
    }

   //================================================================================================
    public function destroy(Request $request,$id){
        $cliente=$this->clienteRep->eliminar($id);

        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'cliente ' . $cliente->nombre. ', eliminada',
                'id'      => $cliente->cliente_id
            ));
        }

        return Redirect::route('business.cliente.index');
    }

}