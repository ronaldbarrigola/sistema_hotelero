<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\DatoFactura;
use Carbon\Carbon;
use DB;

class DatoFacturaRepository{

    public function obtenerDatoFacturaPorNit($nit){
        return DatoFactura::where("nit",$nit)->where("estado",1)->first();
    }

    public function insertarDesdeRequest(Request $request){
        $nit=$request['pago_nit'];
        $nombre=$request['pago_nombre'];
        $celular=$request['pago_celular'];
        $email=$request['pago_email'];

        //Validaciones
        $nit=($nit!=null)?$nit:"";
        $nombre=($nombre!=null)?$nombre:"";
        $celular=($celular!=null)?$celular:"";
        $email=($email!=null)?$email:"";

        $datoFactura=$this->obtenerDatoFacturaPorNit($nit);
        if($datoFactura==null){
            $datoFactura=new DatoFactura();
            $datoFactura->nit=$nit;
            $datoFactura->nombre=$nombre;
            $datoFactura->celular=$celular;
            $datoFactura->email=$email;
            $datoFactura->usuario_alta_id=Auth::user()->id;
            $datoFactura->usuario_modif_id=Auth::user()->id;
            $datoFactura->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $datoFactura->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $datoFactura->estado=1;
            $datoFactura->save();
        } else {
            $datoFactura->nombre=$nombre;
            $datoFactura->celular=$celular;
            $datoFactura->email=$email;
            $datoFactura->usuario_modif_id=Auth::user()->id;
            $datoFactura->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $datoFactura->update();
        }

        return $datoFactura;
    }

}//fin clase
