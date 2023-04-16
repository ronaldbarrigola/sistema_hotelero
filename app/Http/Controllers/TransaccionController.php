<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\TransaccionRepository;

class TransaccionController extends Controller
{
    protected $transaccionRep;

    public function __construct(TransaccionRepository $transaccionRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->transaccionRep=$transaccionRep;
    }

     public function index(Request $request){
        if($request->ajax()){
            return $this->transaccionRep->obtenerTransaccionPorReservaId(58);
        }else{
            return view('business.transaccion.index');
        }
    }

    public function store(Request $request){
        $transaccion=$this->transaccionRep->insertarDesdeRequest($request);
        return response()->json(array ('transaccion'=>$transaccion));
    }

    public function edit(Request $request){
        $id=$request['transaccion_id'];
        $transaccion=$this->transaccionRep->obtenerTransaccionPorId($id);
        return response()->json(array ('transaccion'=>$transaccion));
    }

    public function update(Request $request, $id){
        $request->request->add(['id'=>$id]);
        $transaccion=$this->transaccionRep->modificarDesdeRequest($request);
        return  $transaccion;
    }

    public function destroy(Request $request,$id){
        $transaccion=$this->transaccionRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'transaccion '.$transaccion->id.', eliminada',
                'id'      => $transaccion->id
            ));
        }
        return Redirect::route('business.transaccion.index');
    }
}
