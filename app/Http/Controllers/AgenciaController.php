<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Base\AgenciaRepository;
use App\Repositories\Base\SucursalRepository;

class AgenciaController extends Controller
{
    protected $agenciaRep;
    protected $SucursalRep;
    //===========================================================================================================
    //CONSTRUCTOR
    //===========================================================================================================
    public function __construct(AgenciaRepository $agenciaRep,SucursalRepository $sucursalRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->agenciaRep=$agenciaRep;
        $this->SucursalRep=$sucursalRep;
    }
    //===========================================================================================================
    //index (GET)
    //===========================================================================================================
    public function index(Request $request){
        $sucursal_id=$request->get("s_id");
        if($request->ajax()){
            return $this->agenciaRep->obtenerAgenciasPorSucursalDataTables($sucursal_id);
        }else{
            $sucursal=$this->SucursalRep->obtenerSucursalPorId($sucursal_id);
            return view('base.seguridad.agencia.index',['sucursal'=>$sucursal]);
        }
    }
    ///===========================================================================================================
    //CREATE (GET)
    //===========================================================================================================
    public function create(Request $request){
        $sucursal_id=$request->get("s_id");
        $sucursal=$this->SucursalRep->obtenerSucursalPorId($sucursal_id);
        $lista_sucursales=$this->SucursalRep->obtenerSucursales();
        return view('base.seguridad.agencia.create_edit',['agencia'=>null,"sucursal"=>$sucursal,"lista_sucursales"=>$lista_sucursales]);
    }
    //===========================================================================================================
    //store  (INSERTAR) (POST)
    //===========================================================================================================
    public function store(Request $request){
        $request['usuario_alta_id']=Auth::user()->id;//enviando id de agencia logueado
        //$request['predeterminado']=$request->get('predeterminado') === 'on' ? 1 : 0;
        $agencia=$this->agenciaRep->insertarDesdeRequest($request);
        return Redirect::to('seguridad/agencias?s_id='.$agencia->sucursal_id);//esto va al index
    }
    //===========================================================================================================
    //show (GET)
    //===========================================================================================================
    public function show($id){
        // $agencia=$this->agenciaRep->obtenerAgenciaPorId($id);
        // return view('base.seguridad.agencia.index',['agencia'=>$agencia]);
    }

    //===========================================================================================================
    //Edit (GET)
    //===========================================================================================================
    public function edit($id){
        $agencia=$this->agenciaRep->obtenerAgenciaPorId($id);
        $sucursal=$this->SucursalRep->obtenerSucursalPorId($agencia->sucursal_id);
        $lista_sucursales=$this->SucursalRep->obtenerSucursales();
        return view('base.seguridad.agencia.create_edit',['agencia'=>$agencia,"sucursal"=>$sucursal,"lista_sucursales"=>$lista_sucursales]);
    }

    //===========================================================================================================
    //update  (PATCH)(POST)
    //===========================================================================================================
    public function update(Request $request, $id){
        $request['id']=$id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
        $request['agencia_modificacion_id']=Auth::user()->id;//enviando id de agencia logueado
        $agencia=$this->agenciaRep->modificarDesdeRequest($request);
        return Redirect::to('seguridad/agencias?s_id='.$agencia->sucursal_id);//esto va al index
    }
    //===========================================================================================================
    //destroy (DELETE)(POST) //esto es para eliminar con ajax y refrescar solo la fila que se elimino <tr> ajax
    //===========================================================================================================
    public function destroy(Request $request,$id){
        $agencia=$this->agenciaRep->eliminar($id);
        //si la solicitud es ajax no resfresca la pagina solo un <tr>, caso contrario si refresca toda la pagina
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'agencia ' . $agencia->id. ', eliminado',
                'id'      => $agencia->id
            ));
        }
        return Redirect::route('base.seguridad.agencia.index');
    }

    //===========================================================================================================
    //Listar agencias por idSucursal
    //===========================================================================================================
    public function obtenerAgenciasPorSucursal(Request $request,$idSucursal){
        if($request->ajax()){
            $agencias=$this->agenciaRep->obtenerAgenciasPorSucursal($idSucursal);// si no encuentra devuelve null
            return response()->json($agencias);
        }
    }

    //===========================================================================================================

} //FIN CLASE
