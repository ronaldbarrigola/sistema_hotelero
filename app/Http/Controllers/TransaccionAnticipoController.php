<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\TransaccionAnticipoRepository;
use App\Repositories\Business\FormaPagoRepository;
use App\Repositories\Business\HuespedRepository;


class TransaccionAnticipoController extends Controller
{
    protected $transaccionAnticipoRep;
    protected $formaPagoRep;
    protected $huespedRep;

    public function __construct(TransaccionAnticipoRepository $transaccionAnticipoRep,FormaPagoRepository $formaPagoRep,HuespedRepository $huespedRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->transaccionAnticipoRep=$transaccionAnticipoRep;
        $this->formaPagoRep=$formaPagoRep;
        $this->huespedRep=$huespedRep;
    }

    public function create(Request $request){
        $transaccion_id=($request["transaccion_id"]!=null)?$request["transaccion_id"]:0;
        $TransaccionPago=$this->transaccionAnticipoRep->obtenerAnticipoPorTransaccionId($transaccion_id);
        $forma_pago_id="";
        if($TransaccionPago!=null){
            $pago=$TransaccionPago->pago; //Cargar entidad relacion 1 a N inversa
            $forma_pago_id=$pago->forma_pago_id;
        }
        $formaPagos=$this->formaPagoRep->obtenerFormaPagos();
        return response()->json(array ('formaPagos'=>$formaPagos,'forma_pago_id'=>$forma_pago_id));
    }

    public function store(Request $request){
        $transaccion=null;
        $request->request->add(['transaccion_id'=>$request->get('anticipo_transaccion_id')]);
        $request->request->add(['forma_pago_id'=>$request->get('anticipo_forma_pago_id')]);
        $transaccionPago=$this->transaccionAnticipoRep->insertarDesdeRequest($request);
        if($transaccionPago!=null){
            $transaccion=$transaccionPago->transaccion; //Cargar entidad relacion 1 a N inversa
        }
        return response()->json(array ('transaccion'=>$transaccion,'transaccionPago'=>$transaccionPago));
    }
}
