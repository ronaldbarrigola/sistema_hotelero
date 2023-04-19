<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\TransaccionPagoRepository;


class TransaccionPagoController extends Controller
{
    protected $transaccionPagoRep;

    public function __construct(TransaccionPagoRepository $transaccionPagoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->transaccionPagoRep=$transaccionPagoRep;
    }

     public function index(Request $request){
        if($request->ajax()){
           // return $this->transaccionPagoRep->obtenerTransaccionesDataTables($reserva_id);
        }else{
           // return view('business.transaccion.index');
        }
    }

    public function store(Request $request){
        $transaccionPago=$this->transaccionPagoRep->insertarDesdeRequest($request);
        return response()->json(array ('transaccionPago'=>$transaccionPago));
    }

    public function edit(Request $request){
        $id=$request['transaccion_pago_id'];
        $transaccionPago=$this->transaccionPagoRep->obtenerTransaccionPorId($id);
        return response()->json(array ('transaccionPago'=>$transaccionPago));
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
