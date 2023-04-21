<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\CategoriaRepository;
use App\Repositories\Business\HotelProductoRepository;

class HotelProductoController extends Controller
{
    protected $categoriaRep;
    protected $hotelProductoRep;

    public function __construct(CategoriaRepository $categoriaRep,HotelProductoRepository $hotelProductoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->categoriaRep=$categoriaRep;
        $this->hotelProductoRep=$hotelProductoRep;
    }

     public function index(Request $request){
        if($request->ajax()){
            return $this->hotelProductoRep->obtenerHotelProductosDataTables();
        }else{
            $categorias=$this->categoriaRep->obtenerCategorias();
            return view('business.hotel_producto.index',['categorias'=>$categorias]);
        }
    }

    public function store(Request $request){
        $response=$this->hotelProductoRep->insertarDesdeRequest($request);
        return response()->json(array ('response'=>$response));
    }

    public function activate(Request $request){
        $hotel_producto=$this->hotelProductoRep->activarHotelProducto($request);
        return response()->json(array ('hotel_producto'=>$hotel_producto));
    }

    public function edit(Request $request){
        $id=$request['hotel_producto_id'];
        $hotel_producto=$this->hotelProductoRep->obtenerHotelProductoPorId($id);
        $producto=$hotel_producto->producto;
        return response()->json(array ('hotel_producto'=>$hotel_producto,'producto'=>$producto));
    }

    public function update(Request $request, $id){
        $request->request->add(['id'=>$id]);
        $hotelProducto=$this->hotelProductoRep->modificarDesdeRequest($request);
        return  $hotelProducto;
    }

    public function destroy(Request $request,$id){
        $hotelProducto=$this->hotelProductoRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'producto '.$hotelProducto->descripcion.', eliminada',
                'id'      => $hotelProducto->id
            ));
        }
    }
}
