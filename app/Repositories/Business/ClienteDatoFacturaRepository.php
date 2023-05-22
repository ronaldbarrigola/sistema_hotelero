<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\ClienteDatoFactura;
use Carbon\Carbon;
use DB;

class ClienteDatoFacturaRepository{

    public function obtenerClienteDatoFacturaPorClienteId($cliente_id){
       return ClienteDatoFactura::where("cliente_id",$cliente_id)->where("estado",1)->first();
    }

    public function insertarDesdeRequest(Request $request){
        $cliente_id=$request['pago_cliente_id'];
        $datofactura_id=$request['datofactura_id'];
        $cliente_id=($cliente_id!=null)?$cliente_id:0;
        $datofactura_id=($datofactura_id!=null)?$datofactura_id:"";

        $clienteDatoFactura=$this->obtenerClienteDatoFacturaPorClienteId($cliente_id);
        if($clienteDatoFactura==null){
            $clienteDatoFactura=new ClienteDatoFactura();
            $clienteDatoFactura->cliente_id=$cliente_id;
            $clienteDatoFactura->datofactura_id=$datofactura_id;
            $clienteDatoFactura->usuario_alta_id=Auth::user()->id;
            $clienteDatoFactura->usuario_modif_id=Auth::user()->id;
            $clienteDatoFactura->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $clienteDatoFactura->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $clienteDatoFactura->estado=1;
            $clienteDatoFactura->save();
        } else {
            $clienteDatoFactura->datofactura_id=$datofactura_id;
            $clienteDatoFactura->usuario_modif_id=Auth::user()->id;
            $clienteDatoFactura->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $clienteDatoFactura->update();
        }

        return $clienteDatoFactura;
    }

}//fin clase
