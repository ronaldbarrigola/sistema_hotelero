<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\TransaccionPagoRepository;
use App\Repositories\Business\FormaPagoRepository;


class TransaccionPagoController extends Controller
{
    protected $transaccionPagoRep;
    protected $formaPagoRep;

    public function __construct(TransaccionPagoRepository $transaccionPagoRep,FormaPagoRepository $formaPagoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->transaccionPagoRep=$transaccionPagoRep;
        $this->formaPagoRep=$formaPagoRep;
    }

     public function index(Request $request){
        if($request->ajax()){
           // return $this->transaccionPagoRep->obtenerTransaccionesDataTables($reserva_id);
        }else{
           // return view('business.transaccion.index');
        }
    }

    public function create(){
        $formaPagos=$this->formaPagoRep->obtenerFormaPagos();
        return response()->json(array ('formaPagos'=>$formaPagos));
    }

    public function store(Request $request){
        $transaccionPago=$this->transaccionPagoRep->insertarDesdeRequest($request);
        return response()->json(array ('transaccionPago'=>$transaccionPago));
    }

    public function edit(Request $request){
        $id=$request['transaccion_pago_id'];
        $formaPagos=$this->formaPagoRep->obtenerFormaPagos();
        $transaccionPago=$this->transaccionPagoRep->obtenerTransaccionPorId($id);
        return response()->json(array ('transaccionPago'=>$transaccionPago,'formaPagos'=>$formaPagos));
    }

    public function update(Request $request, $id){
        // $request->request->add(['id'=>$id]);
        // $transaccionPago=$this->transaccionPagoRep->modificarDesdeRequest($request);
        // return  $transaccionPago;
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
