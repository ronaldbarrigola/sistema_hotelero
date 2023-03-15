<?php

namespace App\Repositories\Business;
use App\Repositories\Base\PersonaRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Cliente;
use Carbon\Carbon;
use DB;

class ClienteRepository{

    protected $personaRep;

    public function __construct(PersonaRepository $personaRep){
        $this->personaRep=$personaRep;
    }


    public function obtenerClientes(){
       $clientes=DB::table('bas_persona as p')
       ->join('cli_cliente as c','c.id','=','p.id')
       ->join('bas_tipo_doc as d','d.id','=','p.tipo_doc_id')
       ->leftjoin('cli_pais as cp','cp.id','=','c.pais_id')
       ->leftjoin('cli_ciudad as cc','cc.id','=','c.ciudad_id')
       ->select('c.id','p.doc_id','d.nombre as tipo_documento',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'cp.descripcion as pais','cc.descripcion as ciudad','p.direccion','p.telefono','p.email')
       ->where('p.estado','=','1')
       ->orderBy('c.id','desc')
       ->get();
       return  $clientes;
    }

    public function obtenerClientesDataTables(){
        $clientes=$this->obtenerClientes();
        return datatables()->of($clientes)->toJson();
    }

    public function obtenerClientePorId($id){
        return Cliente::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $cliente=null;
        try{
            DB::beginTransaction();

            $request->request->add(['usuario_alta_id'=>Auth::user()->id]);
            $request->request->add(['usuario_modif_id'=>Auth::user()->id]);

            $persona=$this->personaRep->insertarDesdeRequest($request);

            if($persona!=null){
                $cliente=new Cliente($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
                $cliente->id=$persona->id;
                $cliente->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $cliente->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $cliente->estado=1;
                $cliente->save();
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $cliente;
    }

    public function modificarDesdeRequest(Request $request){
        $cliente=null;
        try{
            DB::beginTransaction();
            $request->request->add(['usuario_modif_id'=>Auth::user()->id]);
            $persona=$this->personaRep->modificarDesdeRequest($request);
            $cliente=$this->obtenerClientePorId($request->get('id'));

            if ( is_null($cliente) ){
                $cliente=new Cliente($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
                $cliente->id=$persona->id;
                $cliente->usuario_alta_id=Auth::user()->id;
                $cliente->usuario_modif_id=Auth::user()->id;
                $cliente->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $cliente->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $cliente->estado=1;
                $cliente->save();
            } else {
                $cliente->fill($request->all()); //llena datos desde el array entrante en el request.
                $cliente->usuario_modif_id=Auth::user()->id;
                $cliente->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $cliente->update();
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $cliente;
    }

    public function eliminar($id){

        $this->personaRep->eliminar($id);

        $cliente=$this->obtenerClientePorId($id);
        if ( is_null($cliente) ){
            App::abort(404);
        }
        $cliente->estado='0';
        $cliente->update();
        return $cliente;
    }

}//fin clase
