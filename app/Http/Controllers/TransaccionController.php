<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\TransaccionRepository;
use App\Repositories\Business\HotelProductoRepository;


class TransaccionController extends Controller
{
    protected $transaccionRep;
    protected $hotelProductoRep;

    public function __construct(TransaccionRepository $transaccionRep,HotelProductoRepository $hotelProductoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->transaccionRep=$transaccionRep;
        $this->hotelProductoRep=$hotelProductoRep;
    }

     public function index(Request $request){
        if($request->ajax()){
            $reserva_id=($request['reserva_id']!=null)?$request['reserva_id']:0;
            return $this->transaccionRep->obtenerTransaccionesDataTables($reserva_id);
        }else{
           // return view('business.transaccion.index');
        }
    }

    public function create(){
        $hotelProductos=$this->hotelProductoRep->obtenerHotelProductos();
        return response()->json(array ('hotel_productos'=>$hotelProductos));
    }

    public function store(Request $request){
        $transaccion=$this->transaccionRep->insertarDesdeRequest($request);
        return response()->json(array ('transaccion'=>$transaccion));
    }

    public function edit(Request $request){
        $id=$request['transaccion_id'];
        $transaccion=$this->transaccionRep->obtenerTransaccionPorId($id);
        $hotelProductos=$this->hotelProductoRep->obtenerHotelProductos();
        return response()->json(array ('transaccion'=>$transaccion,'hotel_productos'=>$hotelProductos));
    }

    public function update(Request $request, $id){
        $request->request->add(['id'=>$id]);
        $transaccion=$this->transaccionRep->modificarDesdeRequest($request);
        return response()->json(array ('transaccion'=>$transaccion));
    }

    public function destroy(Request $request,$id){
        $transaccion=$this->transaccionRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'transaccion '.$transaccion->id.', eliminada',
                'id'      => $transaccion->id
            ));
        }
    }
}
