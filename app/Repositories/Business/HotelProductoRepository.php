<?php

namespace App\Repositories\Business;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\HotelProducto;
use App\Entidades\Business\Producto;
use App\Repositories\Business\ProductoRepository;
use Carbon\Carbon;
use DB;

class HotelProductoRepository{

    protected $productoRep;

    //===constructor=============================================================================================
    public function __construct(ProductoRepository $productoRep){
        $this->productoRep=$productoRep;
    }

    public function obtenerHotelProductos(){
        $hotelProductos=DB::table('pro_producto as p')
            ->leftJoin('pro_hotel_producto as hp', function ($join) {
                $join->on('hp.producto_id', '=', 'p.id')->where('hp.estado', '=', 1);
            })
            ->leftJoin('pro_categoria as c', function ($join) {
                $join->on('c.id', '=', 'p.categoria_id')->where('c.estado', '=', 1);
            })
            ->select('hp.id','p.id as producto_id','p.descripcion as producto',DB::raw('IFNULL(hp.precio,0) as precio'),'c.descripcion as categoria')
           //->where('hp.agencia_id','=',Auth::user()->agencia_id)
            ->where('p.estado','=','1')
            ->orderBy('hp.id','desc')
            ->get();

        return $hotelProductos;
    }

    public function obtenerHotelProductosDataTables(){
        $hotelProductos=$this->obtenerHotelProductos();
        return datatables()->of($hotelProductos)->toJson();
    }

    public function obtenerHotelProductoPorId($id){
        return HotelProducto::find($id);
    }

    public function obtenerProductoPorDescripcion($descripcion){
        $producto=Producto::where("descripcion",$descripcion)->where("estado",1)->first();
        return $producto->hotelProductos->where("agencia_id",Auth::user()->agencia_id)->where("estado",1)->first();
    }

    public function insertarDesdeRequest(Request $request){
        $response="";
        try{
            DB::beginTransaction();
            $datos_json=$this->productoRep->insertarDesdeRequest($request);
            $response = $datos_json->getData()->response;
            $producto_id=$datos_json->getData()->producto->id;
            if($response=="201"){ //201 Create  200: Registro existente
                $hotelProducto=new HotelProducto($request->all());
                $hotelProducto->producto_id=$producto_id;
                $hotelProducto->agencia_id=Auth::user()->agencia_id;
                $hotelProducto->usuario_alta_id=Auth::user()->id;
                $hotelProducto->usuario_modif_id=Auth::user()->id;
                $hotelProducto->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $hotelProducto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $hotelProducto->estado=1;
                $hotelProducto->save();
            }
            DB::commit();
        }catch(\Exception $e){
            $response="ERROR";
            DB::rollback();
        }
        return $response;
    }

    public function activarHotelProducto(Request $request){
        $hotelProducto=null;
        $producto=$this->productoRep->obtenerProductoPorId($request->get('producto_id'));
        if(!is_null(!$producto)){
            $hotelProducto=$producto->hotelProductos->where("estado",0)->first();
            if(is_null(!$hotelProducto)){
                $hotelProducto=new HotelProducto();
                $hotelProducto->producto_id=$producto->id;
                $hotelProducto->agencia_id=Auth::user()->agencia_id;
                $hotelProducto->usuario_alta_id=Auth::user()->id;
                $hotelProducto->usuario_modif_id=Auth::user()->id;
                $hotelProducto->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $hotelProducto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $hotelProducto->estado=1;
                $hotelProducto->save();
            } else {
                $hotelProducto->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
                $hotelProducto->usuario_modif_id=Auth::user()->id;
                $hotelProducto->estado='1';
                $hotelProducto->update();
            }

        }
        return $hotelProducto;
    }

    public function modificarDesdeRequest(Request $request){
        $hotelProducto=null;
        try{
            DB::beginTransaction();

            $request->request->add(['producto_id'=>$request->get('producto_id')]);
            $this->productoRep->modificarDesdeRequest($request);

            $hotelProducto=$this->obtenerHotelProductoPorId($request->get('id'));
            $hotelProducto->fill($request->all()); //llena datos desde el array entrante en el request.
            $hotelProducto->usuario_modif_id=Auth::user()->id;
            $hotelProducto->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $hotelProducto->update();
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return $hotelProducto;
    }

    public function eliminar($id){
        $hotelProducto=$this->obtenerHotelProductoPorId($id);
        if ( is_null($hotelProducto) ){
            App::abort(404);
        }
        $hotelProducto->usuario_modif_id=Auth::user()->id;
        $hotelProducto->fecha_modificacion =Carbon::now('America/La_Paz')->toDateTimeString();
        $hotelProducto->estado='0';
        $hotelProducto->update();
        return $hotelProducto;
    }

}
