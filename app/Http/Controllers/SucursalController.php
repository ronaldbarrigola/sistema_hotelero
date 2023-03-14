<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Base\SucursalRepository;
use App\Repositories\Base\CiudadRepository;

class SucursalController extends Controller
{
    protected $sucursalRep;
    protected $CiudadRep;
    //===========================================================================================================
    //CONSTRUCTOR
    //===========================================================================================================
    public function __construct(SucursalRepository $sucursalRep,CiudadRepository $ciudadRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->sucursalRep=$sucursalRep;
        $this->CiudadRep=$ciudadRep;
    }
    //===========================================================================================================
    //index (GET)
    //===========================================================================================================
    public function index(Request $request){
        if($request->ajax()){
            return $this->sucursalRep->obtenerSucursalesDataTables();
        }else{
            return view('base.seguridad.sucursal.index');
        }
    }
    ///===========================================================================================================
    //CREATE (GET)
    //===========================================================================================================
    public function create(){
        $ciudades=$this->CiudadRep->obtenerCiudades();
        return view('base.seguridad.sucursal.create_edit',['sucursal'=>null,'ciudades'=>$ciudades]);
    }
    //===========================================================================================================
    //store  (INSERTAR) (POST)
    //===========================================================================================================
    public function store(Request $request){
        $request['usuario_alta_id']=Auth::user()->id;//enviando id de sucursal logueado
        //$request['predeterminado']=$request->get('predeterminado') === 'on' ? 1 : 0;
        $this->sucursalRep->insertarDesdeRequest($request);
        return Redirect::to('seguridad/sucursales');//esto va al index
    }
    //===========================================================================================================
    //show (GET)
    //===========================================================================================================
    public function show($id){
        //$sucursal=$this->sucursalRep->obtenerSucursalPorId($id);
        $url="/seguridad/agencias?s_id=".$id;
        return Redirect::to($url);
    }

    //===========================================================================================================
    //Edit (GET)
    //===========================================================================================================
    public function edit($id){
        $sucursal=$this->sucursalRep->obtenerSucursalPorId($id);
        $ciudades=$this->CiudadRep->obtenerCiudades();
        return view('base.seguridad.sucursal.create_edit',['sucursal'=>$sucursal,'ciudades'=>$ciudades]);
    }

    //===========================================================================================================
    //update  (PATCH)(POST)
    //===========================================================================================================
    public function update(Request $request, $id){
        $request['id']=$id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
        $request['sucursal_modificacion_id']=Auth::user()->id;//enviando id de sucursal logueado
        $this->sucursalRep->modificarDesdeRequest($request);
        return Redirect::to('seguridad/sucursales');//esto va al index
    }
    //===========================================================================================================
    //destroy (DELETE)(POST) //esto es para eliminar con ajax y refrescar solo la fila que se elimino <tr> ajax
    //===========================================================================================================
    public function destroy(Request $request,$id){
        $sucursal=$this->sucursalRep->eliminar($id);
        //si la solicitud es ajax no resfresca la pagina solo un <tr>, caso contrario si refresca toda la pagina
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'sucursal ' . $sucursal->id. ', eliminado',
                'id'      => $sucursal->id
            ));
        }
        return Redirect::route('base.seguridad.sucursal.index');
    }

    //===========================================================================================================

} //FIN CLASE
