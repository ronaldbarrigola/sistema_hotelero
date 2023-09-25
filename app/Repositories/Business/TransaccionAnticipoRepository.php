<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\TransaccionPago;
use App\Repositories\Business\PagoRepository;
use App\Repositories\Business\ImporteRepository;
use Carbon\Carbon;
use DB;

class TransaccionAnticipoRepository{
    protected $pagoRep;
    protected $importeRep;
    public function __construct(PagoRepository $pagoRep,ImporteRepository $importeRep){
        $this->pagoRep=$pagoRep;
        $this->importeRep=$importeRep;
    }

    public function obtenerTransaccionPagoPorId($id){
        return TransaccionPago::find($id);
    }

    public function obtenerAnticipoPorTransaccionId($id){
        $transaccionPago=TransaccionPago::where("transaccion_id",$id)->where("tipo_transaccion_id","A")->where("estado",1)->first();
        return $transaccionPago;
    }

    public function insertarDesdeRequest(Request $request){
        $transaccionPago=null;
        try{
            DB::beginTransaction();

            $transaccion_id=$request->get('transaccion_id');
            $monto=$request['anticipo'];
            $transaccionPago=$this->obtenerAnticipoPorTransaccionId($transaccion_id);

            if(is_null($transaccionPago)){
                $pago_id=0;
                $pago=$this->pagoRep->insertarDesdeRequest($request);
                if(!is_null($pago)){
                    $pago_id= $pago->id;
                }

                $transaccionPago=new TransaccionPago();
                $transaccionPago->pago_id=$pago_id;
                $transaccionPago->transaccion_id=$transaccion_id;
                $transaccionPago->monto=$monto;
                $transaccionPago->detalle="ANTICIPO";
                $transaccionPago->tipo_transaccion_id="A";//P:PAGO A:ANTICIPO
                $transaccionPago->usuario_alta_id=Auth::user()->id;
                $transaccionPago->usuario_modif_id=Auth::user()->id;
                $transaccionPago->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->estado=1;
                $transaccionPago->save();

                //Insertar Importe
                $request->request->add(['pago_id'=>$pago_id]);
                $request->request->add(['monto'=>$monto]);
                $this->importeRep->insertarDesdeRequest($request);
            } else {
                $transaccionPago->monto=$monto;
                $transaccionPago->usuario_modif_id=Auth::user()->id;
                $transaccionPago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->update();

                //Modificar pago
                $request->request->add(['pago_id'=>$transaccionPago->pago_id]); //Usado para modificar pago e importe
                $this->pagoRep->modificarDesdeRequest($request);

                //Modificar Importe
                $request->request->add(['monto'=>$monto]);// Solo para pagos que no son multiples
                $this->importeRep->modificarImportePorPagoId($request);

            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $transaccionPago;
    }

}//fin clase
