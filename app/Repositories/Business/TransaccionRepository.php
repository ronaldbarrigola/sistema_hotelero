<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Transaccion;
use App\Repositories\Business\TipoTransaccionRepository;
use Carbon\Carbon;
use DB;

class TransaccionRepository{

    protected $tipoTransaccionRep;

    //===constructor=============================================================================================
    public function __construct(TipoTransaccionRepository $tipoTransaccionRep){
        $this->tipoTransaccionRep=$tipoTransaccionRep;
    }

    public function obtenerTransacciones(){
       $transacciones=DB::table('con_transaccion as tr')
       ->leftjoin('pro_hotel_producto as h','h.id','=','tr.hotel_producto_id')
       ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
       ->select('tr.id',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),'p.descripcion','tr.detalle','tr.cantidad','tr.precio_unidad','tr.descuento_porcentaje','tr.descuento',DB::raw('(CASE WHEN tr.factor=-1 THEN IFNULL(tr.monto,0)  ELSE 0 END) AS cargo'),DB::raw('(CASE WHEN tr.factor=1  THEN IFNULL(tr.monto,0)  ELSE 0 END) AS pago'))
       ->where('h.agencia_id','=',Auth::user()->agencia_id)
       ->where('tr.estado','=','1')
       ->orderBy('tr.id','desc')
       ->get();
       return  $transacciones;
    }

    public function obtenerTransaccionDataTables(){
        $transacciones=$this->obtenerTransacciones();
        return datatables()->of($transacciones)->toJson();
    }

    public function obtenerTransaccionPorId($id){
        return Transaccion::find($id);
    }

    public function insertarDesdeRequest(Request $request){

        $tipoTransaccionId=$request->get('tipo_transaccion_id');
        $factor=$this->tipoTransaccionRep->factorReservaPorId($tipoTransaccionId);

        //validaciones
        $cantidad=($request['cantidad']!=null)?$request['cantidad']:0;
        $precio_unidad=($request['precio_unidad']!=null)?$request['precio_unidad']:0;
        $descuento_porcentaje=($request['descuento_porcentaje']!=null)?$request['descuento_porcentaje']:0;
        $descuento=($request['descuento']!=null)?$request['descuento']:0;
        $monto=($request['monto']!=null)?$request['monto']:0;
        $hotel_producto_id=($request['hotel_producto_id']!=null)?$request['hotel_producto_id']:0;

        $transaccion=new Transaccion($request->all());
        $transaccion->factor=$factor; //1:Cargo -1:Pago
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

        return $transaccion;
    }

    public function modificarDesdeRequest(Request $request){
        //validaciones
        $cantidad=($request['cantidad']!=null)?$request['cantidad']:0;
        $precio_unidad=($request['precio_unidad']!=null)?$request['precio_unidad']:0;
        $descuento_porcentaje=($request['descuento_porcentaje']!=null)?$request['descuento_porcentaje']:0;
        $descuento=($request['descuento']!=null)?$request['descuento']:0;
        $monto=($request['monto']!=null)?$request['monto']:0;
        $hotel_producto_id=($request['hotel_producto_id']!=null)?$request['hotel_producto_id']:0;

        $transaccion=$this->obtenerTransaccionPorId($request->get('transaccion_id'));
        $transaccion->fill($request->all()); //llena datos desde el array entrante en el request.
        $transaccion->cantidad=$cantidad;
        $transaccion->precio_unidad=$precio_unidad;
        $transaccion->descuento_porcentaje=$descuento_porcentaje;
        $transaccion->descuento=$descuento;
        $transaccion->monto=$monto;
        $transaccion->hotel_producto_id=$hotel_producto_id;
        $transaccion->usuario_modif_id=Auth::user()->id;
        $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $transaccion->update();
        return  $transaccion;
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
