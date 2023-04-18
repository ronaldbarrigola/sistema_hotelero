<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Transaccion;
use App\Repositories\Business\CargoRepository;
use Carbon\Carbon;
use DB;

class TransaccionRepository{

    protected $cargoRep;

    public function __construct(CargoRepository $cargoRep){
        $this->cargoRep=$cargoRep;
    }

    public function obtenerTransacciones($reserva_id){
        $transaccion=DB::table('con_cargo as c')
        ->leftjoin('con_transaccion as tr','tr.cargo_id','=','c.id')
        ->leftjoin('pro_hotel_producto as h','h.id','=','tr.hotel_producto_id')
        ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
        ->select('c.id as cargo_id','tr.id',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y") as fecha'),'p.descripcion as producto','tr.detalle','tr.cantidad','tr.precio_unidad',DB::raw('tr.cantidad*tr.precio_unidad as total'),'tr.descuento_porcentaje','tr.descuento','tr.monto as cargo',DB::raw('(SELECT IFNULL(sum(tp.monto),0) FROM con_transaccion_pago tp WHERE tp.transaccion_id = tr.id AND tp.estado=1) as pago'))
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('c.reserva_id','=',$reserva_id)
        ->where('tr.estado','=','1')
        ->orderBy('tr.fecha','desc')
        ->orderBy('tr.id','desc')
        ->get();

        //Calcular Saldo
        if($transaccion!=null){
            $saldo=0;
            foreach($transaccion as $row){
                $saldo=$row->cargo - $row->pago;
                $row->saldo=round($saldo,2);
            }
        }

        return  $transaccion;
    }

    public function obtenerTransaccionesDataTables($reserva_id){
        $transaccion=$this->obtenerTransacciones($reserva_id);
        return datatables()->of($transaccion)->toJson();
    }

    public function obtenerTransaccionPorId($id){
        return Transaccion::find($id);
    }

    public function insertarDesdeReserva(Request $request){

        $reserva_id=($request['foreign_reserva_id']!=null)?$request['foreign_reserva_id']:0;

        //Crear Cargo
        $cargo_id=0;
        $cargo=$this->cargoRep->insertarDesdeRequest($request);
        if(!is_null($cargo)){
            $cargo_id= $cargo->id;
        }

        $hotel_producto_id = $request->get('hotel_producto_id');
        $fecha_ini = $request->get('fecha_ini');
        $cantidad=$request['reserva_cantidad'];
        $precio_unidad=$request['reserva_precio_unidad'];
        $descuento_porcentaje=$request['reserva_descuento_porcentaje'];
        $descuento=$request['reserva_descuento'];

        //Validaciones
        $cantidad=($cantidad!=null)?$cantidad:0;
        $precio_unidad=($precio_unidad!=null)?$precio_unidad:0;
        $descuento_porcentaje=($descuento_porcentaje!=null)?$descuento_porcentaje:0;
        $descuento=($descuento!=null)?$descuento:0;

        $descuento_porcentaje=round($descuento_porcentaje/$cantidad,2);
        $descuento=round($descuento/$cantidad,2);
        $fecha = Carbon::parse($fecha_ini);
        for ($i = 1; $i <= $cantidad; $i++) {
            $transaccion=new Transaccion();
            $transaccion->venta_id=0;
            $transaccion->cargo_id=$cargo_id;
            $transaccion->reserva_id=$reserva_id;
            $transaccion->cantidad=1;
            $transaccion->precio_unidad=$precio_unidad;
            $transaccion->descuento_porcentaje=$descuento_porcentaje;
            $transaccion->descuento=$descuento;
            $transaccion->monto=round($precio_unidad-$descuento,2); //Solo ingresa 1 unidad
            $transaccion->hotel_producto_id=$hotel_producto_id;
            $transaccion->usuario_alta_id=Auth::user()->id;
            $transaccion->usuario_modif_id=Auth::user()->id;
            $transaccion->fecha= $fecha->toDateTimeString();
            $transaccion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->estado=1;
            $transaccion->save();
            $fecha = $fecha->addDay();
        }
    }

    public function insertarDesdeRequest(Request $request){

        $modulo=($request['modulo']!=null)?$request['modulo']:"";
        $reserva_id=($request['foreign_reserva_id']!=null)?$request['foreign_reserva_id']:0;
        $cargo_id=0;
        if($modulo=="RESERVA"){
            $cargo=$this->cargoRep->insertarDesdeRequest($request);
            if(!is_null($cargo)){
                $cargo_id= $cargo->id;
            }
        }

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
                    $transaccion->reserva_id=$reserva_id;
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

        return $transaccion;
    }

    public function modificarDesdeRequest(Request $request){

         $id= $request->get('id');
         $cantidad=$request['cantidad'];
         $precio_unidad=$request['precio_unidad'];
         $descuento_porcentaje=$request['descuento_porcentaje'];
         $descuento=$request['descuento'];
         $monto=$request['monto'];

         $cantidad=($cantidad!=null)?$cantidad:0;
         $precio_unidad=($precio_unidad!=null)?$precio_unidad:0;
         $descuento_porcentaje=($descuento_porcentaje!=null)?$descuento_porcentaje:0;
         $descuento=($descuento!=null)?$descuento:0;
         $monto=($monto!=null)?$monto:0;

         $transaccion=$this->obtenerTransaccionPorId($id);
         $transaccion->cantidad=$cantidad;
         $transaccion->precio_unidad=$precio_unidad;
         $transaccion->descuento_porcentaje=$descuento_porcentaje;
         $transaccion->descuento=$descuento;
         $transaccion->monto=$monto;
         $transaccion->usuario_modif_id=Auth::user()->id;
         $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
         $transaccion->update();

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
