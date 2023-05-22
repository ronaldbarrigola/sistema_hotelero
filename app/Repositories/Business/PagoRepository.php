<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\Pago;
use App\Repositories\Business\DatoFacturaRepository;
use App\Repositories\Business\ClienteDatoFacturaRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PagoRepository{

    protected $datoFacturaRep;
    protected $clienteDatoFacturaRep;

    public function __construct(DatoFacturaRepository $datoFacturaRep,ClienteDatoFacturaRepository $clienteDatoFacturaRep){
        $this->datoFacturaRep=$datoFacturaRep;
        $this->clienteDatoFacturaRep=$clienteDatoFacturaRep;
    }

    public function obtenerPagoPorId($id){
        return Pago::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $pago=null;
        try{
            DB::beginTransaction();

            $cliente_id=$request['pago_cliente_id'];
            $nit=$request['pago_nit'];
            $nombre=$request['pago_nombre'];
            $celular=$request['pago_celular'];
            $email=$request['pago_email'];
            $detalle=$request['pago_detalle'];

            //Validaciones
            $cliente_id=($cliente_id!=null)?$cliente_id:0;
            $nit=($nit!=null)?$nit:"";
            $nombre=($nombre!=null)?$nombre:"";
            $celular=($celular!=null)?$celular:"";
            $email=($email!=null)?$email:"";
            $detalle=($detalle!=null)?$detalle:"PAGO";

            $pago=new Pago();
            $pago->cliente_id=$cliente_id;
            $pago->nit=$nit;
            $pago->nombre=$nombre;
            $pago->celular=$celular;
            $pago->email=$email;
            $pago->detalle=$detalle;
            $pago->agencia_id=Auth::user()->agencia_id;
            $pago->usuario_alta_id=Auth::user()->id;
            $pago->usuario_modif_id=Auth::user()->id;
            $pago->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $pago->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $pago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $pago->estado=1;
            $pago->save();

            //DatoFactura
            if($nit!=""){
                $datoFactura=$this->datoFacturaRep->insertarDesdeRequest($request);
                if($datoFactura!=null){
                    $request->request->add(['datofactura_id'=> $datoFactura->id]);
                    $this->clienteDatoFacturaRep->insertarDesdeRequest($request);
                }
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $pago;
    }

    public function modificarDesdeRequest(Request $request){
        $pago=$this->obtenerPagoPorId($request->get('pago_id'));
        $pago->fill($request->all());
        $pago->usuario_modif_id=Auth::user()->id;
        $pago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
        $pago->update();
        return  $pago;
    }

    public function eliminar($id){
        $pago=$this->obtenerPagoPorId($id);
        if ( is_null($pago) ){
            App::abort(404);
        }
        $pago->estado='0';
        $pago->update();
        return $pago;
    }

}//fin clase
