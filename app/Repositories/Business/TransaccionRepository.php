<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Transaccion;
use Carbon\Carbon;
use DB;

class TransaccionRepository{

    public function obtenerTransaccionPorCargoId($cargo_id){
        $transacciones=DB::table('con_transaccion as tr')
        ->leftjoin('pro_hotel_producto as h','h.id','=','tr.hotel_producto_id')
        ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
        ->select('tr.id',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),'p.descripcion as producto','tr.detalle','tr.cantidad','tr.precio_unidad','tr.descuento_porcentaje','tr.descuento','tr.monto')
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('tr.cargo_id','=',$cargo_id)
        ->where('tr.estado','=','1')
        ->orderBy('tr.fecha','desc')
        ->orderBy('tr.id','desc')
        ->get();
        return  $transacciones;
    }

    public function obtenerTransaccionPorId($id){
        return Transaccion::find($id);
    }

    public function insertarDesdeRequest(Request $request){

        //Obtener array
        $vec_estado= $request->get('vec_estado');
        $vec_hotel_producto = $request->get('vec_hotel_producto_id');
        $vec_cantidad=$request['vec_cantidad'];
        $vec_precio_unidad=$request['vec_precio_unidad'];
        $vec_descuento_porcentaje=$request['vec_descuento_porcentaje'];
        $vec_descuento=$request['vec_descuento'];
        $vec_monto=$request['vec_monto'];

        //variable
        $venta_id=($request->get('venta_id')!=null)?$request->get('venta_id'):0;
        $cargo_id=($request->get('cargo_id')!=null)?$request->get('cargo_id'):0;

        $index=0;
        foreach ($vec_hotel_producto as $hotel_producto_id) {
                $estado=$vec_estado[$index];

                //Validaciones
                $cantidad=($vec_cantidad[$index]!=null)?$vec_cantidad[$index]:0;
                $precio_unidad=($vec_precio_unidad[$index]!=null)?$vec_precio_unidad[$index]:0;
                $descuento_porcentaje=($vec_descuento_porcentaje[$index]!=null)?$vec_descuento_porcentaje[$index]:0;
                $descuento=($vec_descuento[$index]!=null)?$vec_descuento[$index]:0;
                $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;

                if($estado=='nuevo'){
                    $transaccion=new Transaccion();
                    $transaccion->venta_id=$venta_id;
                    $transaccion->cargo_id=$cargo_id;
                    $transaccion->cantidad=$cantidad;
                    $transaccion->precio_unidad=$precio_unidad;
                    $transaccion->descuento_porcentaje=$descuento_porcentaje;
                    $transaccion->descuento=$descuento;
                    $transaccion->monto=$monto;
                    $transaccion->hotel_producto_id=$hotel_producto_id;
                    $transaccion->usuario_alta_id=Auth::user()->id;
                    $transaccion->usuario_modif_id=Auth::user()->id;
                    $transaccion->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
                    $transaccion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                    $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                    $transaccion->estado=1;
                    $transaccion->save();
                }
                $index++;
        }

        //validaciones
        $cantidad=($request['cantidad']!=null)?$request['cantidad']:0;
        $precio_unidad=($request['precio_unidad']!=null)?$request['precio_unidad']:0;
        $descuento_porcentaje=($request['descuento_porcentaje']!=null)?$request['descuento_porcentaje']:0;
        $descuento=($request['descuento']!=null)?$request['descuento']:0;
        $monto=($request['monto']!=null)?$request['monto']:0;
        $hotel_producto_id=($request['hotel_producto_id']!=null)?$request['hotel_producto_id']:0;

        return $transaccion;
    }

    public function modificarDesdeRequest(Request $request){
         //Obtener array
         $vec_estado= $request->get('vec_estado');
         $vec_transaccion_id= $request->get('vec_transaccion_id');
         $vec_hotel_producto = $request->get('vec_hotel_producto_id');
         $vec_cantidad=$request['vec_cantidad'];
         $vec_precio_unidad=$request['vec_precio_unidad'];
         $vec_descuento_porcentaje=$request['vec_descuento_porcentaje'];
         $vec_descuento=$request['vec_descuento'];
         $vec_monto=$request['vec_monto'];


        $index=0;
        foreach ($vec_hotel_producto as $hotel_producto_id) {
            $estado=$vec_estado[$index];
             //Validaciones
            $cantidad=($vec_cantidad[$index]!=null)?$vec_cantidad[$index]:0;
            $precio_unidad=($vec_precio_unidad[$index]!=null)?$vec_precio_unidad[$index]:0;
            $descuento_porcentaje=($vec_descuento_porcentaje[$index]!=null)?$vec_descuento_porcentaje[$index]:0;
            $descuento=($vec_descuento[$index]!=null)?$vec_descuento[$index]:0;
            $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;

            if($estado=='nuevo'){
                $transaccion=new Transaccion();
                $transaccion->venta_id=$venta_id;
                $transaccion->cargo_id=$cargo_id;
                $transaccion->cantidad=$cantidad;
                $transaccion->precio_unidad=$precio_unidad;
                $transaccion->descuento_porcentaje=$descuento_porcentaje;
                $transaccion->descuento=$descuento;
                $transaccion->monto=$monto;
                $transaccion->hotel_producto_id=$hotel_producto_id;
                $transaccion->usuario_alta_id=Auth::user()->id;
                $transaccion->usuario_modif_id=Auth::user()->id;
                $transaccion->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccion->estado=1;
                $transaccion->save();
            }

            if($estado=='guardado'){
                $transaccion= $this->obtenerTransaccionPorId($vec_transaccion_id[$index]);
                if($transaccion!=null){
                    $transaccion->cantidad=$cantidad;
                    $transaccion->precio_unidad=$precio_unidad;
                    $transaccion->descuento_porcentaje=$descuento_porcentaje;
                    $transaccion->descuento=$descuento;
                    $transaccion->monto=$monto;
                    $transaccion->hotel_producto_id=$hotel_producto_id;
                    $transaccion->usuario_modif_id=Auth::user()->id;
                    $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                    $transaccion->update();
                }
            }

            if($estado=='eliminado'){
                $this->eliminar($vec_transaccion_id[$index]);
            }

            $index++;
        }
    }

    public function eliminar($id){
        $transaccion=$this->obtenerTransaccionPorId($id);
        if ( is_null($transaccion) ){
            App::abort(404);
        }
        $transaccion->estado='0';
        $transaccion->update();
        return $transaccion;
    }

}//fin clase
