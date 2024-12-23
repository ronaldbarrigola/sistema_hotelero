<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Transaccion;
use App\Entidades\Business\Reserva;
use App\Repositories\Business\CargoRepository;
use App\Repositories\Business\TransaccionAnticipoRepository;
use App\Repositories\Business\TransaccionDetalleRepository;
use App\Repositories\Business\GrupoRepository;
use Carbon\Carbon;
use DB;

class TransaccionRepository{

    protected $cargoRep;
    protected $transaccionAnticipoRep;
    protected $transaccionDetalleRep;
    protected $grupoRep;

    public function __construct(CargoRepository $cargoRep,TransaccionDetalleRepository $transaccionDetalleRep,TransaccionAnticipoRepository $transaccionAnticipoRep,GrupoRepository $grupoRep){
        $this->cargoRep=$cargoRep;
        $this->transaccionDetalleRep=$transaccionDetalleRep;
        $this->transaccionAnticipoRep=$transaccionAnticipoRep;
        $this->grupoRep=$grupoRep;
    }

    public function obtenerTransacciones($reserva_id){

        $grupo_id=$this->grupoRep->obtenerGrupoIdPorReservaId($reserva_id);

        $transaccion=DB::table('con_cargo as c')
        ->join('res_reserva as r','r.id','=','c.reserva_id')
        ->leftjoin('gob_habitacion as hab','hab.id','=','r.habitacion_id')
        ->leftjoin('con_transaccion as tr','tr.cargo_id','=','c.id')
        ->leftjoin('pro_hotel_producto as h','h.id','=','tr.hotel_producto_id')
        ->leftjoin('pro_producto as p','p.id','=','h.producto_id')
        ->select('c.id as cargo_id','tr.id','hab.num_habitacion',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y") as fecha'),'p.descripcion as producto','tr.detalle','tr.cantidad','tr.precio_unidad',DB::raw('tr.cantidad*tr.precio_unidad as total'),DB::raw('(SELECT IFNULL(sum(tp.monto),0) FROM con_transaccion_pago tp WHERE tp.tipo_transaccion_id="A" AND tp.transaccion_id = tr.id AND tp.estado=1) as anticipo'),'tr.descuento_porcentaje','tr.descuento','tr.monto as cargo',DB::raw('(SELECT IFNULL(sum(tp.monto),0) FROM con_transaccion_pago tp WHERE tp.transaccion_id = tr.id AND tp.estado=1) as pago'))
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where(function ($query) use ($reserva_id, $grupo_id) {
            $query->where('tr.reserva_id', '=', $reserva_id)
                  ->orWhere('r.grupo_id', '=', $grupo_id);
        })
        ->where('tr.estado','=','1')
        ->where('r.estado','=','1')
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

    public function saldo($reserva_id){
        $transaccion = Transaccion::withSum(['transaccionPago' => function ($query) { //transaccionPago esta definido en la entidad transaccion
           $query->where('estado',1);
        }], 'monto')->where("reserva_id",$reserva_id)->where("estado",1)->get();

        //Calcular Pago
        $pago=0;
        if($transaccion!=null){
            foreach($transaccion as $row){
                $pago=$pago + $row->transaccion_pago_sum_monto; //Se genera en forma autoamtica la variable transaccion_pago_sum_monto  en atributes de la variable $transaccion
            }
        }

        $cargo =Transaccion::where("reserva_id",$reserva_id)->where("estado",1)->sum('monto');
        $saldo= $cargo-$pago;
        return $saldo;
    }

    public function obtenerTransaccionesDataTables($reserva_id){
        $transaccion=$this->obtenerTransacciones($reserva_id);
        return datatables()->of($transaccion)->toJson();
    }

    public function obtenerTransaccionPorId($id){
        return Transaccion::find($id);
    }

    public function insertarDesdeReserva(Request $request){ //Registro de transaccion correspondiente a un Reserva
        $transaccion=null;
        try{
            DB::beginTransaction();
            $reserva_id=($request['foreign_reserva_id']!=null)?$request['foreign_reserva_id']:0;
            //Crear Cargo
            $cargo_id=0;
            $cargo=$this->cargoRep->insertarDesdeRequest($request);
            if(!is_null($cargo)){
                $cargo_id= $cargo->id;
            }

            $hotel_producto_id = $request->get('hotel_producto_id');
            $cantidad=$request['reserva_cantidad'];
            $precio_unidad=$request['reserva_precio_unidad'];
            $descuento_porcentaje=$request['reserva_descuento_porcentaje'];
            $descuento=$request['reserva_descuento'];
            $anticipo=$request['reserva_anticipo'];
            $detalle=$request['detalle'];

            //Validaciones
            $cantidad=($cantidad!=null)?$cantidad:0;
            $precio_unidad=($precio_unidad!=null)?$precio_unidad:0;
            $descuento_porcentaje=($descuento_porcentaje!=null)?$descuento_porcentaje:0;
            $descuento=($descuento!=null)?$descuento:0;
            $anticipo=($anticipo!=null)?$anticipo:0;
            $detalle=($detalle!=null)?$detalle:"";

            $transaccion=new Transaccion();
            $transaccion->venta_id=0;
            $transaccion->cargo_id=$cargo_id;
            $transaccion->reserva_id=$reserva_id;
            $transaccion->cantidad=$cantidad;
            $transaccion->precio_unidad=$precio_unidad;
            $transaccion->descuento_porcentaje=$descuento_porcentaje;
            $transaccion->descuento=$descuento;
            $transaccion->monto=round($cantidad*$precio_unidad-$descuento,2);
            $transaccion->hotel_producto_id=$hotel_producto_id;
            $transaccion->detalle=$detalle;
            $transaccion->transaccion_base=1; //Transaccion para modificar cantidad, precio_unidad, descuento, monto desde reserva
            $transaccion->usuario_alta_id=Auth::user()->id;
            $transaccion->usuario_modif_id=Auth::user()->id;
            $transaccion->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $transaccion->estado=1;
            $transaccion->save();

            if($anticipo>0){
                $request->request->add(['transaccion_id'=> $transaccion->id]);
                $request->request->add(['anticipo'=>$anticipo]);
                $this->transaccionAnticipoRep->insertarDesdeRequest($request);
            }

            $request->request->add(['transaccion_id'=> $transaccion->id]);
            $this->transaccionDetalleRep->insertarDesdeRequest($request);

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $transaccion;

    }

    public function insertarDesdeRequest(Request $request){
        $transaccion=null;
        try{
            DB::beginTransaction();

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

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $transaccion;
    }

    public function modificarDesdeRequest(Request $request){
        $transaccion=null;
        try{
            DB::beginTransaction();
            $id= $request->get('id');
            $cantidad=$request['cantidad'];
            $precio_unidad=$request['precio_unidad'];
            $descuento_porcentaje=$request['descuento_porcentaje'];
            $descuento=$request['descuento'];
            $monto=$request['monto'];
            $anticipo=$request['reserva_anticipo'];

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

            //Insertar modificar Anticipo
            $request->request->add(['transaccion_id'=> $transaccion->id]);
            $request->request->add(['anticipo'=>$anticipo]);
            $this->transaccionAnticipoRep->insertarDesdeRequest($request);

            $transaccion->transaccionDetalle()->delete();
            $this->transaccionDetalleRep->insertarDesdeRequest($request);
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $transaccion;
    }

    public function eliminar($id){
        $transaccion=$this->obtenerTransaccionPorId($id);
        $transaccion->delete();//Eliminacion logica
        return $transaccion;
    }

}//fin clase
