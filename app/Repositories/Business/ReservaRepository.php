<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Reserva;
use App\Entidades\Business\Transaccion;
use App\Repositories\Business\TransaccionRepository;
use App\Repositories\Business\HotelProductoRepository;
use Carbon\Carbon;
use DB;

class ReservaRepository{

    protected $transaccionRep;
    protected $hotelProductoRep;

    //===constructor=============================================================================================
    public function __construct(TransaccionRepository $transaccionRep,HotelProductoRepository $hotelProductoRep){
        $this->transaccionRep=$transaccionRep;
        $this->hotelProductoRep=$hotelProductoRep;
    }

    public function obtenerReservas(){
        $reservas= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->leftjoin('res_servicio as serv','serv.id','=','r.servicio_id')
        ->leftjoin('gob_tipo_habitacion as th','th.id','=','h.tipo_habitacion_id')
        ->leftjoin('res_paquete as q','q.id','=','r.paquete_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->select('r.id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'h.num_habitacion','th.descripcion as tipo_habitacion','q.descripcion as paquete','serv.descripcion as servicio',DB::raw('DATE_FORMAT(r.fecha_ini,"%d/%m/%Y") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%d/%m/%Y") as fecha_fin'),'r.num_adulto','r.num_nino','cp.descripcion as pais','cc.descripcion as ciudad','r.detalle','er.descripcion as estado_reserva','r.servicio_id')
        ->where('h.agencia_id','=',Auth::user()->agencia_id)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->orderBy('r.id','desc')
        ->get();

        return $reservas;
    }


    public function obtenerReservasDataTables(){
        $reservas=$this->obtenerReservas();
        return datatables()->of($reservas)->toJson();
    }

    public function obtenerReservasTimeLines(){

        //Sumar fechas
            // $endDate = $date->addYear();
            // $endDate = $date->addYears(5);
            // $endDate = $date->addMonth();
            // $endDate = $date->addMonths(5);
            // $endDate = $date->addDay();
            // $endDate = $date->addDay(5);
        //Restar fechas
            // $endDate = $date->subYear();
            // $endDate = $date->subYears(5);
            // $endDate = $date->subMonth();
            // $endDate = $date->subMonths(5);
            // $endDate = $date->subDay();
            // $endDate = $date->subDay(5);

        $fecha_filtro=Carbon::now('America/La_Paz')->subMonth(6); //filtro 6 meses atras
        $reservas= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->select('r.id','r.estado_reserva_id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y") as fecha'),'p.paterno',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'r.habitacion_id','h.num_habitacion',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y-%m-%d %H:%i:%s") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y-%m-%d %H:%i:%s") as fecha_fin'),'er.descripcion as estado_reserva','er.color','er.editable','r.servicio_id',DB::raw('(SELECT IFNULL(sum(tr.monto),0) FROM con_transaccion tr WHERE tr.reserva_id = r.id AND tr.estado=1) as cargo'),DB::raw('(SELECT IFNULL(sum(trp.monto),0) FROM con_transaccion_pago as trp INNER JOIN con_transaccion as tr ON trp.transaccion_id=tr.id WHERE tr.reserva_id = r.id AND tr.estado=1 AND trp.estado=1) as pago'))
        ->whereDate('r.fecha_ini','>=',$fecha_filtro)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->orderBy('r.id','desc')
        ->get();

        //Calcular Saldo
        if($reservas!=null){
            $porcentaje=0;
            foreach($reservas as $row){
                $porcentaje=($row->pago*100)/$row->cargo;
                $row->porcentaje=round($porcentaje,2);
            }
        }

        return $reservas;
    }

    public function obtenerReservaPorId($id){
       return Reserva::find($id);
    }

    public function transaccionPorReservaId($id){ //Para obtener transaccion principal, y si fue eliminado crea una nueva transaccion
        $transaccion=Reserva::find($id)->transacciones->where("transaccion_base",1)->where("estado",1)->first();
        if(is_null($transaccion)){
            $transaccion=new Transaccion();
         }
        return $transaccion;
    }

    public function estadoReserva($id,$estado){
        $response=false;
        $message="";
        $reserva=$this->obtenerReservaPorId($id);
        if(!is_null($reserva)){
            switch ($estado) {
                case 0:
                    $reserva->estado_reserva_id=0;//0: Reserva
                    $reserva->update();
                    $response=true;
                    break;
                case 1:
                    if($reserva->estado_reserva_id==0){
                        $fecha=$reserva->fecha_ini;
                        $fecha_inicial = Carbon::parse($fecha)->format('Y-m-d');
                        $fecha_actual = Carbon::now()->format('Y-m-d');
                        if($fecha_inicial==$fecha_actual){
                            $reserva->estado_reserva_id=1;//1:Check In
                            $reserva->update();
                            $response=true;
                        } else {
                            $message="El check in esta programado para otra fecha. Cambie la fecha de ingreso a la fecha actual para el Check In";
                        }

                    } else {
                       $message="No puede ejecutar la accion Check In";
                    }
                    break;
                case 2:
                    if($reserva->estado_reserva_id==1){ //Verifica si el estado esta en Check In
                       $reserva->estado_reserva_id=2;//2: Stand By
                       $reserva->update();
                       $response=true;
                    } else {
                       $message="No puede ejecutar la accion Stand By";
                    }
                    break;
                case 3:

                    if($reserva->estado_reserva_id==1){ //Verifica si el estado esta en Check In
                        $saldo=$this->transaccionRep->saldo($id);
                        if($saldo>0){
                            $message="No puede ejecutar la accion Check Out, porque tiene saldo pendiente de pago";
                        } else {
                            $reserva->estado_reserva_id=3;//3: Check Out
                            $reserva->update();
                            $response=true;
                        }

                    } else {
                       $message="No puede ejecutar la accion Check Out";
                    }

                    break;
            }

        }

        return response()->json(array ('response'=>$response,'reserva'=>$reserva->estadoReserva,'message'=>$message));
    }


    public function insertarDesdeRequest(Request $request){
        $reserva=null;
        try{
            DB::beginTransaction();

            $fecha_ini=$request->get('fecha_ini');
            $fecha_fin=$request->get('fecha_fin');

            $starDate = Carbon::parse($fecha_ini);
            $endDate = Carbon::parse($fecha_fin);

            $diferencia_en_dias=$starDate->diffInDays($endDate);

            if($diferencia_en_dias==0){
                $fecha_ini=$fecha_ini."T03:00:00"; //03:00 am
                $fecha_fin=$fecha_fin."T21:00:00"; //21:00 pm
            } else {
                $fecha_ini=$fecha_ini."T12:00:00";
                $fecha_fin=$fecha_fin."T12:00:00";
            }

            $reserva=new Reserva($request->all());           ;
            $reserva->fecha_ini=$fecha_ini;
            $reserva->fecha_fin=$fecha_fin;
            $reserva->usuario_alta_id=Auth::user()->id;
            $reserva->usuario_modif_id=Auth::user()->id;
            $reserva->estado_reserva_id=0; //0: Reserva 1:Check In 2: Stand By 3: Check Out
            $reserva->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $reserva->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $reserva->estado=1;
            $reserva->save();

            $descripcion=$reserva->servicio->descripcion; //obtiene datos mediante la relacion 1:N
            $hotel_producto=$this->hotelProductoRep->obtenerProductoPorDescripcion($descripcion);

            $request->request->add(['foreign_reserva_id'=>$reserva->id]);
            $request->request->add(['hotel_producto_id'=>$hotel_producto->id]);

            $this->transaccionRep->insertarDesdeReserva($request);

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $reserva;
    }

    public function modificarDesdeRequest(Request $request){
        $reserva=null;
        try{
            DB::beginTransaction();

            $fecha_ini=$request->get('fecha_ini');
            $fecha_fin=$request->get('fecha_fin');

            $starDate = Carbon::parse($fecha_ini);
            $endDate = Carbon::parse($fecha_fin);

            $diferencia_en_dias=$starDate->diffInDays($endDate);

            if($diferencia_en_dias==0){
                $fecha_ini=$fecha_ini."T03:00:00"; //03:00 am
                $fecha_fin=$fecha_fin."T21:00:00"; //21:00 pm
            } else {
                $fecha_ini=$fecha_ini."T12:00:00";
                $fecha_fin=$fecha_fin."T12:00:00";
            }

            //Validaciones
            $cantidad=($request['reserva_cantidad']!=null)?$request['reserva_cantidad']:0;
            $precio_unidad=($request['reserva_precio_unidad']!=null)?$request['reserva_precio_unidad']:0;
            $descuento_porcentaje=($request['reserva_descuento_porcentaje']!=null)?$request['reserva_descuento_porcentaje']:0;
            $descuento=($request['reserva_descuento']!=null)?$request['reserva_descuento']:0;
            $monto=($request['reserva_monto']!=null)?$request['reserva_monto']:0;

            $reserva=$this->obtenerReservaPorId($request->get('id'));
            if ($reserva!=null){
                $reserva->fill($request->all());
                $reserva->fecha_ini=$fecha_ini;
                $reserva->fecha_fin=$fecha_fin;
                $reserva->usuario_modif_id=Auth::user()->id;
                $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $reserva->update();

                //Modificar cargo
                $descripcion=$reserva->servicio->descripcion; //obtiene datos mediante la relacion 1:N
                $hotel_producto=$this->hotelProductoRep->obtenerProductoPorDescripcion($descripcion);

                $transaccion=$reserva->transacciones->where("transaccion_base",1)->first();//Obtiene reserva base de la tabla cargo

                $request->request->add(['id'=>$transaccion->id]);
                $request->request->add(['hotel_producto_id'=>$hotel_producto->id]);
                $request->request->add(['cantidad'=>$cantidad]);
                $request->request->add(['precio_unidad'=>$precio_unidad]);
                $request->request->add(['descuento_porcentaje'=>$descuento_porcentaje]);
                $request->request->add(['descuento'=>$descuento]);
                $request->request->add(['monto'=>$monto]);

                $transaccion=$reserva->transacciones()->where("transaccion_base",1)->where("estado",1)->first(); //En caso de que la transaccion principal haya sido eliminado, crea un nueva transaccion
                if(!is_null($transaccion)){
                    $this->transaccionRep->modificarDesdeRequest($request);
                } else {
                    $descripcion=$reserva->servicio->descripcion;//obtiene datos mediante la relacion 1:N
                    $hotel_producto=$this->hotelProductoRep->obtenerProductoPorDescripcion($descripcion);
                    $request->request->add(['foreign_reserva_id'=>$reserva->id]);
                    $request->request->add(['hotel_producto_id'=>$hotel_producto->id]);
                    $this->transaccionRep->insertarDesdeReserva($request);
                }

            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $reserva;
    }

    public function eliminar($id){
        $reserva=$this->obtenerReservaPorId($id);
        if ( is_null($reserva) ){
            App::abort(404);
        }
        $reserva->delete();//Eliminacion logica y en cascada con sus relaciones
        return $reserva;
    }

}//fin clase
