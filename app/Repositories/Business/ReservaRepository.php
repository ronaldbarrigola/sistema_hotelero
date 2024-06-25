<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\Reserva;
use App\Entidades\Business\Transaccion;
use App\Repositories\Base\PersonaRepository;
use App\Repositories\Business\TransaccionRepository;
use App\Repositories\Business\HotelProductoRepository;
use App\Repositories\Business\HuespedRepository;
use App\Repositories\Business\ComprobanteRepository;
use Carbon\Carbon;
use DB;

class ReservaRepository{

    protected $transaccionRep;
    protected $hotelProductoRep;
    protected $huespedRep;
    protected $personaRep;
    protected $comprobanteRep;

    //===constructor=============================================================================================
    public function __construct(TransaccionRepository $transaccionRep,HotelProductoRepository $hotelProductoRep,PersonaRepository $personaRep,HuespedRepository $huespedRep,ComprobanteRepository $comprobanteRep){
        $this->transaccionRep=$transaccionRep;
        $this->hotelProductoRep=$hotelProductoRep;
        $this->huespedRep=$huespedRep;
        $this->personaRep=$personaRep;
        $this->comprobanteRep=$comprobanteRep;
    }

    public function obtenerReservas(){
        $reservas= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->leftjoin('res_servicio as serv','serv.id','=','r.servicio_id')
        ->leftjoin('gob_tipo_habitacion as th','th.id','=','h.tipo_habitacion_id')
        ->leftjoin('cli_pais as cp','cp.id','=','r.procedencia_pais_id')
        ->leftjoin('cli_ciudad as cc','cc.id','=','r.procedencia_ciudad_id')
        ->leftjoin('res_canal_reserva as cr','cr.id','=','r.canal_reserva_id')
        ->select('r.id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y %H:%i:%s") as fecha'),'cr.nombre as canal_reserva',DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'h.num_habitacion','th.descripcion as tipo_habitacion','serv.descripcion as servicio',DB::raw('DATE_FORMAT(r.fecha_ini,"%d/%m/%Y") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%d/%m/%Y") as fecha_fin'),'cp.descripcion as pais','cc.descripcion as ciudad','r.detalle','er.descripcion as estado_reserva','r.servicio_id')
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
        ->leftjoin('gob_tipo_habitacion as t','t.id','=','h.tipo_habitacion_id')
        ->leftjoin('res_canal_reserva as cr','cr.id','=','r.canal_reserva_id')
        ->leftjoin('res_grupo as g','g.id','=','r.grupo_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->select('r.id','r.estado_reserva_id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y") as fecha'),'cr.nombre as canal_reserva','p.tipo_persona_id','g.nombre as nombre_grupo','g.color as color_borde',DB::raw('IFNULL(p.nombre,"") as nombre'),DB::raw('IFNULL(p.paterno,"") as paterno'),DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),DB::raw('(SELECT IFNULL(count(*),0) FROM res_huesped i inner join cli_cliente cli on i.cliente_id=cli.id  WHERE i.estado_huesped_id=1 AND i.reserva_id=r.id AND i.estado=1 AND cli.estado=1) as cantidad_huesped_checkin'),'r.habitacion_id','h.num_habitacion','t.descripcion as tipo_habitacion',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y-%m-%d %H:%i:%s") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y-%m-%d %H:%i:%s") as fecha_fin'),'er.descripcion as estado_reserva','er.color','er.editable','r.servicio_id',DB::raw('(SELECT IFNULL(sum(tr.monto),0) FROM con_transaccion tr WHERE tr.reserva_id = r.id AND tr.estado=1) as cargo'),DB::raw('(SELECT IFNULL(sum(trp.monto),0) FROM con_transaccion_pago as trp INNER JOIN con_transaccion as tr ON trp.transaccion_id=tr.id WHERE tr.reserva_id = r.id AND tr.estado=1 AND trp.estado=1) as pago'))
        ->whereDate('r.fecha_ini','>=',$fecha_filtro)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->orderBy('r.id','desc')
        ->get();

        //Calcular porcentaje de pago
        if($reservas!=null){
            $porcentaje=0;
            foreach($reservas as $row){
                if($row->cargo>0){ //Para evitar division entre cero
                   $porcentaje=($row->pago*100)/$row->cargo;
                }
                $row->porcentaje=round($porcentaje,2);
            }
        }

        return $reservas;
    }

    public function obtenerReservasPorIdTimeLines($reserva_id){ //Usado para actualizar datos de pago del item en la linea de tiempo
        $reserva= DB::table('res_reserva as r')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->join('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('gob_tipo_habitacion as t','t.id','=','h.tipo_habitacion_id')
        ->leftjoin('res_canal_reserva as cr','cr.id','=','r.canal_reserva_id')
        ->leftjoin('res_grupo as g','g.id','=','r.grupo_id')
        ->join('res_estado_reserva as er','er.id','=','r.estado_reserva_id')
        ->select('r.id','r.estado_reserva_id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y") as fecha'),'cr.nombre as canal_reserva','p.tipo_persona_id','g.nombre as nombre_grupo','g.color as color_borde',DB::raw('IFNULL(p.nombre,"") as nombre'),DB::raw('IFNULL(p.paterno,"") as paterno'),DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),DB::raw('(SELECT IFNULL(count(*),0) FROM res_huesped i inner join cli_cliente cli on i.cliente_id=cli.id  WHERE i.estado_huesped_id=1 AND i.reserva_id=r.id AND i.estado=1 AND cli.estado=1) as cantidad_huesped_checkin'),'r.habitacion_id','h.num_habitacion','t.descripcion as tipo_habitacion',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y-%m-%d %H:%i:%s") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y-%m-%d %H:%i:%s") as fecha_fin'),'er.descripcion as estado_reserva','er.color','er.editable','r.servicio_id',DB::raw('(SELECT IFNULL(sum(tr.monto),0) FROM con_transaccion tr WHERE tr.reserva_id = r.id AND tr.estado=1) as cargo'),DB::raw('(SELECT IFNULL(sum(trp.monto),0) FROM con_transaccion_pago as trp INNER JOIN con_transaccion as tr ON trp.transaccion_id=tr.id WHERE tr.reserva_id = r.id AND tr.estado=1 AND trp.estado=1) as pago'))
        ->where('r.id','=',$reserva_id)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('er.estado','=','1')
        ->first();

        //Calcular porcentaje de pago
        if($reserva!=null){
            $porcentaje=0;
            if($reserva->cargo>0){ //Para evitar division entre cero
                $porcentaje=($reserva->pago*100)/$reserva->cargo;
            }
            $reserva->porcentaje=round($porcentaje,2);
        }

        return $reserva;
    }

    //Cabecera del comprobante detalle cargo
    public function obtenerInformacionReservaPorId($reserva_id){
        $reserva= DB::table('res_reserva as r')
        ->join('res_servicio as s','s.id','=','r.servicio_id')
        ->join('bas_persona as p','p.id','=','r.cliente_id')
        ->leftjoin('gob_habitacion as h','h.id','=','r.habitacion_id')
        ->leftjoin('gob_tipo_habitacion as t','t.id','=','h.tipo_habitacion_id')
        ->select('r.id',DB::raw('DATE_FORMAT(r.fecha,"%d/%m/%Y") as fecha'),DB::raw('CONCAT(IFNULL(p.nombre,"")," ",IFNULL(p.paterno,"")," ",IFNULL(p.materno,"")) AS cliente'),'h.num_habitacion','t.descripcion as tipo_habitacion',DB::raw('DATE_FORMAT(r.fecha_ini,"%Y-%m-%d %H:%i:%s") as fecha_ini'),DB::raw('DATE_FORMAT(r.fecha_fin,"%Y-%m-%d %H:%i:%s") as fecha_fin'),'s.descripcion as servicio',DB::raw('(SELECT IFNULL(sum(p.monto),0) FROM con_transaccion t inner join con_transaccion_pago p on t.id=p.transaccion_id  WHERE t.reserva_id=r.id AND p.tipo_transaccion_id="A" AND t.estado=1 AND p.estado=1) as anticipo'),DB::raw('(SELECT IFNULL(sum(tr.descuento),0) FROM con_transaccion tr WHERE tr.transaccion_base="1" AND tr.reserva_id=r.id AND tr.estado=1) as descuento'))
        ->where('r.id','=',$reserva_id)
        ->where('r.estado','=','1')
        ->where('p.estado','=','1')
        ->where('s.estado','=','1')
        ->first();
        return $reserva;
    }

    //Detalle del comprobante detalle cargo
    public function obtenerInformacionTransaccionPorReservaId($reserva_id){

        $grupo_id = -1;
        $reserva = Reserva::find($reserva_id);
        if (!is_null($reserva)) {
            $grupo_id = !is_null($reserva->grupo_id) ? $reserva->grupo_id : -1;
        }

        $transaccion_cargo=DB::table('con_transaccion as tr')
                            ->join('res_reserva as r','r.id','=','tr.reserva_id')
                            ->leftjoin('gob_habitacion as hab','hab.id','=','r.habitacion_id')
                            ->join('pro_hotel_producto as hp','hp.id','=','tr.hotel_producto_id')
                            ->join('pro_producto as p','p.id','=','hp.producto_id')
                            ->select('tr.id','hab.num_habitacion',DB::raw('DATE_FORMAT(tr.fecha,"%d/%m/%Y") as fecha'),'p.descripcion as producto','tr.cantidad','tr.precio_unidad','tr.descuento','tr.monto as cargo',DB::raw('(SELECT IFNULL(sum(trp.monto),0) FROM con_transaccion_pago trp WHERE trp.transaccion_id=tr.id AND tr.estado=1 AND trp.estado=1) as pago'),DB::raw("0 as saldo"))
                            ->where(function ($query) use ($reserva_id, $grupo_id) {
                                $query->where('tr.reserva_id', '=', $reserva_id)
                                      ->orWhere('r.grupo_id', '=', $grupo_id);
                            })
                            ->where('tr.estado','=','1')
                            ->where('r.estado','=','1')
                            ->where('hp.estado','=','1')
                            ->where('p.estado','=','1')
                            ->get();

         //Calcular saldo
         if($transaccion_cargo!=null){
            foreach($transaccion_cargo as $row){
                $cargo=($row->cargo!=null)?$row->cargo:0;
                $pago=($row->pago!=null)?$row->pago:0;
                $row->saldo=round($cargo-$pago,2);
            }
        }

        return $transaccion_cargo;
    }

    public function generarComprobanteDetalleCargo($reserva_id){
        $reserva=$this->obtenerInformacionReservaPorId($reserva_id);
        $detalle=$this->obtenerInformacionTransaccionPorReservaId($reserva_id);
        if($reserva!=null&&$detalle!=null){
            $this->comprobanteRep->comprobante_detalle_cargo($reserva,$detalle);
        }
        return $reserva;
    }

    public function obtenerReservaPorId($id){
       return Reserva::find($id);
    }

    // public function obtenerReservaPorGrupoId($id){
    //    return Reserva::where("grupo_id",$id)->where("estado",1)->first();
    // }

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
                case 0: //RESERVA
                    $reserva->estado_reserva_id=0;//0: Reserva
                    $reserva->update();
                    $response=true;
                    break;
                case 1: //CHECK IN
                    if($reserva->estado_reserva_id==0){
                        $disponible=$this->validarHabitacionReserva($reserva->habitacion_id,$reserva->fecha_ini);
                        if($disponible){
                            $fecha=$reserva->fecha_ini;
                            $fecha_reserva=Carbon::parse($fecha)->format('Y-m-d');
                            $fecha_actual = Carbon::now()->format('Y-m-d');
                            $fecha_anterior = Carbon::now()->subDay(1)->format('Y-m-d');
                            if($fecha_reserva==$fecha_anterior||$fecha_reserva==$fecha_actual){
                                $reserva->estado_reserva_id=1;//1:Check In
                                $reserva->update();
                                $response=true;
                            } else {
                                $message="El check in esta programado para otra fecha. Cambie la fecha de ingreso a la fecha actual para el Check In";
                            }
                        } else {
                            $message="No puede realizar la reserva, porque ya existe una reserva en estado Check In";
                        }

                    } else {
                       $message="El estado deberia estar en Reserva, para ejecutar la accion Check In";
                    }
                    break;
                case 2: //STAND BY
                    if($reserva->estado_reserva_id==1){ //Verifica si el estado esta en Check In
                       $reserva->estado_reserva_id=2;//2: Stand By
                       $reserva->update();
                       $response=true;
                    } else {
                       $message="El estado deberia estar en Check In, para ejecutar la accion Stand By";
                    }
                    break;
                case 3: //CHECK OUT
                    if($reserva->estado_reserva_id==1){ //Verifica si el estado esta en Check In
                        $saldo=$this->transaccionRep->saldo($id);
                        if($saldo>0){
                            $message="No puede ejecutar la accion Check Out, porque tiene saldo pendiente de pago";
                        } else {
                            $reserva->estado_reserva_id=3;//3: Check Out
                            $reserva->update();
                            //Ejecutar Chek Out para todos los huespedes de la reserva
                            $this->huespedRep->checkOutPorReservaId($reserva->id);
                            $response=true;
                        }
                    } else {
                       $message="El estado deberia estar en Check In, para ejecutar la accion Check Out";
                    }

                    break;
            }

        }

        return response()->json(array ('response'=>$response,'reserva'=>$reserva->estadoReserva,'message'=>$message));
    }

    public function validarHabitacionReserva($habitacion_id,$fecha_ini){
        $response=true;
        $fecha_fin=DB::table('res_reserva as r')
        ->selectRaw('MAX(r.fecha_fin) as fecha')
        ->where('r.habitacion_id','=',$habitacion_id)
        ->where('r.estado_reserva_id','=',1) //En check in
        ->where('r.estado','=','1')
        ->first()->fecha;

        if($fecha_fin!=null){
            $fecha_ini=Carbon::parse($fecha_ini);
            $fecha_fin=Carbon::parse($fecha_fin);
            if ($fecha_fin->greaterThan($fecha_ini)) {
                //La fecha_fin es menor que la fecha_ini
                $response=false;
            }
        }
       return $response;
    }

    public function insertarDesdeRequest(Request $request){
        $reserva=null;
        try{
            DB::beginTransaction();

            $fecha_ini=$request->get('fecha_ini');
            $fecha_fin=$request->get('fecha_fin');

            $hora_ini=$request->get('hora_ini');
            $hora_fin=$request->get('hora_fin');

            $fecha_ini=$fecha_ini."T".$hora_ini;
            $fecha_fin=$fecha_fin."T".$hora_fin;

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
            $request->request->add(['pago_cliente_id'=>$reserva->cliente_id]); //En caso de que exista anticipo

            $this->transaccionRep->insertarDesdeReserva($request);

            $persona=$this->personaRep->obtenerPersonaPorId($reserva->cliente_id); //El id cliente es lo mismo que id persona
            if(!is_null($persona)){
                $tipo_persona_id=$persona->tipo_persona_id;
                if($tipo_persona_id!="J"){ //Si no es persona juridica puede ser huesped
                    $this->huespedRep->insertarHuesped($reserva->id,$reserva->cliente_id);
                }
            }

            $this->generarComprobanteDetalleCargo($reserva->id); //El partial esta declarado en layouts/plantilla.blade.php

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

            $hora_ini=$request->get('hora_ini');
            $hora_fin=$request->get('hora_fin');

            $fecha_ini=$fecha_ini."T".$hora_ini;
            $fecha_fin=$fecha_fin."T".$hora_fin;

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

                $request->request->add(['hotel_producto_id'=>$hotel_producto->id]);
                $request->request->add(['cantidad'=>$cantidad]);
                $request->request->add(['precio_unidad'=>$precio_unidad]);
                $request->request->add(['descuento_porcentaje'=>$descuento_porcentaje]);
                $request->request->add(['descuento'=>$descuento]);
                $request->request->add(['monto'=>$monto]);

                $transaccion=$reserva->transacciones()->where("transaccion_base",1)->where("estado",1)->first(); //En caso de que la transaccion principal haya sido eliminado, crea un nueva transaccion
                if(!is_null($transaccion)){
                    $request->request->add(['id'=>$transaccion->id]);
                    $this->transaccionRep->modificarDesdeRequest($request);
                } else {
                    $descripcion=$reserva->servicio->descripcion;//obtiene datos mediante la relacion 1:N
                    $hotel_producto=$this->hotelProductoRep->obtenerProductoPorDescripcion($descripcion);
                    $request->request->add(['foreign_reserva_id'=>$reserva->id]);
                    $request->request->add(['hotel_producto_id'=>$hotel_producto->id]);
                    $this->transaccionRep->insertarDesdeReserva($request);
                }

            }

            $this->generarComprobanteDetalleCargo($reserva->id);

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return  $reserva;
    }

    public function validarEliminacion($id){ //Usado para eliminar desde calendario
        $response=false;
        $message="";
        $reserva=$this->obtenerReservaPorId($id);
        if($reserva->estado_reserva_id==0){
            $response=true;
        } else {
            $transacciones=$this->transaccionRep->obtenerTransacciones($id);
            if($transacciones==null){
                $response=true;
            } else {
                $message="No puede eliminar la reserva, porque ya tiene operaciones registradas";
            }
        }
        return response()->json(array ('response'=>$response,'message'=>$message));
    }

    public function eliminar($id){
        $response=false;
        $message="";
        $reserva=$this->obtenerReservaPorId($id);
        if($reserva->estado_reserva_id==0){
            $reserva->usuario_modif_id=Auth::user()->id;
            $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $reserva->delete();//Eliminacion logica y en cascada con sus relaciones
            $response=true;
        } else {
            $transacciones=$this->transaccionRep->obtenerTransacciones($id);
            if($transacciones==null){
                $reserva->usuario_modif_id=Auth::user()->id;
                $reserva->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $reserva->delete();//Eliminacion logica y en cascada con sus relaciones
                $response=true;
            } else {
                $message="No puede eliminar la reserva, porque ya tiene operaciones registradas";
            }
        }
        return response()->json(array ('response'=>$response,'reserva'=>$reserva,'message'=>$message));
    }

}//fin clase
