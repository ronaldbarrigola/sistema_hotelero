<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\TransaccionPagoRepository;
use App\Repositories\Business\FormaPagoRepository;
use App\Repositories\Business\HuespedRepository;


class TransaccionPagoController extends Controller
{
    protected $transaccionPagoRep;
    protected $formaPagoRep;
    protected $huespedRep;

    public function __construct(TransaccionPagoRepository $transaccionPagoRep,FormaPagoRepository $formaPagoRep,HuespedRepository $huespedRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->transaccionPagoRep=$transaccionPagoRep;
        $this->formaPagoRep=$formaPagoRep;
        $this->huespedRep=$huespedRep;
    }

    public function create(Request $request){
        $reserva_id=($request["reserva_id"]!=null)?$request["reserva_id"]:0;
        $formaPagos=$this->formaPagoRep->obtenerFormaPagos();
        $huespedes=$this->huespedRep->obtenerClienteHuesped($reserva_id);
        return response()->json(array ('formaPagos'=>$formaPagos,"huespedes"=>$huespedes));
    }

    public function store(Request $request){
        $transaccion=null;
        $transaccionPago=$this->transaccionPagoRep->insertarDesdeRequest($request);
        if($transaccionPago!=null){
            $transaccion=$transaccionPago->transaccion; //Cargar entidad relacion 1 a N inversa
        }
        return response()->json(array ('transaccion'=>$transaccion,'transaccionPago'=>$transaccionPago));
    }

    public function edit(Request $request){
        $id=$request['transaccion_pago_id'];
        $formaPagos=$this->formaPagoRep->obtenerFormaPagos();
        $transaccionPago=$this->transaccionPagoRep->obtenerTransaccionPorId($id);
        return response()->json(array ('transaccionPago'=>$transaccionPago,'formaPagos'=>$formaPagos));
    }

    public function destroy(Request $request,$id){
        $transaccionPago=$this->transaccionPagoRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'transaccion pago '.$transaccionPago->id.', eliminada',
                'id'      => $transaccionPago->id
            ));
        }
    }
}
