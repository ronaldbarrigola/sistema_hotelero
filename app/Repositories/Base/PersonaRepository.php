<?php

namespace App\Repositories\Base;
use App\Entidades\Base\Persona;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;

class PersonaRepository{

    public function obtenerPersonasDataTables(){
        return datatables()->of(
            DB::table('bas_persona as p')
            ->leftjoin('bas_tipo_doc as t','t.id','=','p.tipo_doc_id')
            ->leftjoin('bas_ciudad as c','c.id','=','p.ciudad_exp_id')
            ->leftjoin('bas_sexo as s','s.id','=','p.sexo_id')
            ->select(DB::raw('p.id, p.nombre,p.paterno, p.materno,s.nombre as sexo, p.fecha_nac,
                              t.abreviacion as tipo_doc,p.doc_id, c.abreviacion as ciudad_exp,p.email,
                              p.telefono,p.direccion,
                              (SELECT count(*) FROM bas_usuario where id=p.id) as usuario_activado,
                              (SELECT count(*) FROM vnt_vendedor where id=p.id) as vendedor_activado'
                              ))
            ->where('p.estado','=','1')
            ->orderBy('p.id','desc')
            ->get()
        )->toJson();
    }

     public function obtenerPersonas(){
        $personas=DB::table('bas_persona as p')
           ->select('p.id','p.doc_id','p.nombre','p.paterno','p.materno',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS nombre_completo'))
           ->where('p.estado','=','1')
           ->get();
       return $personas;
    }

    public function obtenerPersonasSimpleDifId($id){
        return DB::table('bas_persona')
               ->where('id','<>',$id)
               ->where('estado','=',1)->get();
    }

    public function obtenerPersonaPorId($id){
        return Persona::find($id);
    }

    public function buscarPorNumDocId($id){
        return DB::table('bas_persona')
        ->where('doc_id','=',trim($id))
        ->get();
    }

    public function buscarPersonaClientePorDocId($doc_id){//Para validar numero de documento de personacliente
        $personaCliente=DB::table('bas_persona as p')
           ->leftjoin('cli_cliente as c','c.id','=','p.id')
           ->select('p.id','p.doc_id','p.nombre','p.paterno','p.materno','p.sexo_id','p.fecha_nac','p.tipo_doc_id','p.estado_civil_id','p.email','p.telefono','p.direccion','c.pais_id','c.ciudad_id','c.profesion_id','c.empresa_id','c.detalle')
           ->where('p.doc_id','=',$doc_id)
           ->where('p.estado','=','1')
           ->first();
       return $personaCliente;
    }

    public function insertarDesdeRequest(Request $request){
        $persona=new Persona($request->all());//CREA OBJETO CON TODOS LOS CAMPOS RECIBIDOS DEL REQUEST
        $persona->estado=1;
        $persona->save();
        return $persona;
    }

    public function modificarDesdeRequest(Request $request){
        try{
            DB::beginTransaction();
            //GUARDANDO PERSONA

            $persona=$this->obtenerPersonaPorId($request->get('id'));
            $persona->fill($request->all()); //llena datos desde el array entrante en el request.
           // $this->llenarDatosComunesStoreUpdate($persona,$request);
            $persona->update();
            DB::commit();
            return $persona;
        }catch(\Exception $e){
            DB::rollback();
            $errors=$e->getMessage();
            //return new JsonResponde($errors,500);
            //return $errors;
            echo $errors;
        }
    }

    public function eliminar($id){
        $persona=$this->obtenerPersonaPorId($id);
        if ( is_null($persona) ){
            App::abort(404);
        }
        //$persona->delete();
        $persona->estado='0';
        $persona->update();
        return $persona;
    }


}//fin clase
