<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PagoRepository{

    public function obtenerPagoPorId($id){
        return Pago::find($id);
    }

    public function insertarDesdeRequest(Request $request){

        $nombre=$request['pago_nombre'];
        $nit=$request['pago_nit'];
        $email=$request['pago_email'];
        $detalle=$request['pago_detalle'];

        //Validaciones
        $nombre=($nombre!=null)?$nombre:"";
        $nit=($nit!=null)?$nit:"";
        $email=($email!=null)?$email:"";
        $detalle=($detalle!=null)?$detalle:"PAGO";

        $pago=new Pago($request->all());
        $pago->nit=$nit;
        $pago->email=$email;
        $pago->detalle=$detalle;
        $pago->agencia_id=Auth::user()->agencia_id;
        $pago->usuario_alta_id=Auth::user()->id;
        $pago->usuario_modif_id=Auth::user()->id;
        $pago->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
        $pago->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pago->estado=1;
        $pago->save();
        return $pago;
    }

    public function modificarDesdeRequest(Request $request){
        $pago=$this->obtenerPagoPorId($request->get('pago_id'));
        $pago->fill($request->all());
        $pago->usuario_modif_id=Auth::user()->id;
        $pago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pago->update();
        return  $pago;
    }

    public function eliminar($id){
        $pago=$this->obtenerPagoPorId($id);
        if ( is_null($pago) ){
            App::abort(404);
        }
        $pago->estado='0';
        $pago->update();
        return $pago;
    }

}//fin clase
