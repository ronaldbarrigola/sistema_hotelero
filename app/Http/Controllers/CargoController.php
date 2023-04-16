<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\CargoRepository;
use App\Repositories\Business\TransaccionRepository;
use App\Repositories\Business\HotelProductoRepository;

class CargoController extends Controller
{
    protected $cargoRep;
    protected $transaccionRep;
    protected $hotelProductoRep;

    public function __construct(CargoRepository $cargoRep,TransaccionRepository $transaccionRep,HotelProductoRepository $hotelProductoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->cargoRep=$cargoRep;
        $this->transaccionRep=$transaccionRep;
        $this->hotelProductoRep=$hotelProductoRep;
    }

    public function index(Request $request){
        if($request->ajax()){
            return $this->cargoRep->obtenerCargoPorReservaIdDataTables(64);
        }else{
            $hotelProductos=$this->hotelProductoRep->obtenerHotelProductos();
            return view('business.cargo.index',['hotel_productos'=>$hotelProductos]);
        }
    }

    public function edit(Request $request){
        $id=$request['cargo_id'];
        $cargo=$this->cargoRep->obtenerCargoPorId($id);
        $detalle=null;
        if(!is_null($cargo)){
            $detalle=$this->transaccionRep->obtenerTransaccionPorCargoId($cargo->id);
        }
        return response()->json(array ('cargo'=>$cargo,'detalle'=>$detalle));
    }

    public function store(Request $request){
        $cargo=$this->cargoRep->insertarDesdeRequest($request);
        return response()->json(array ('cargo'=>$cargo));
    }

    public function update(Request $request, $id){
        $request->request->add(['id'=>$id]);
        $cargo=$this->cargoRep->modificarDesdeRequest($request);
        return response()->json(array ('cargo'=>$cargo));
    }

    public function destroy(Request $request,$id){
        $cargo=$this->cargoRep->eliminar($id);
        return response()->json(array ('cargo'=>$cargo));
    }
}
