<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entidades\Business\TransaccionPago;
use App\Repositories\Business\PagoRepository;
use Carbon\Carbon;
use DB;

class TransaccionPagoRepository{
    protected $pagoRep;
    public function __construct(PagoRepository $pagoRep){
        $this->pagoRep=$pagoRep;
    }

    public function obtenerTransaccionPagoPorId($id){
        return TransaccionPago::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $transaccionPago=null;
        try{
            DB::beginTransaction();

            $pago_id=0;
            $pago=$this->pagoRep->insertarDesdeRequest($request);
            if(!is_null($pago)){
                $pago_id= $pago->id;
            }

            //Obtener array
            $vec_transaccion_id = $request->get('p_transaccion_id');
            $vec_monto=$request['p_monto'];

            $index=0;
            foreach ($vec_transaccion_id as $transaccion_id) {
                //Validaciones
                $transaccion_id=($vec_transaccion_id[$index]!=null)?$vec_transaccion_id[$index]:0;
                $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;
                $transaccionPago=new TransaccionPago();
                $transaccionPago->pago_id=$pago_id;
                $transaccionPago->transaccion_id=$transaccion_id;
                $transaccionPago->monto=$monto;
                $transaccionPago->detalle="PAGO";
                $transaccionPago->usuario_alta_id=Auth::user()->id;
                $transaccionPago->usuario_modif_id=Auth::user()->id;
                $transaccionPago->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $transaccionPago->estado=1;
                $transaccionPago->save();
                $index++;
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return $transaccionPago;
    }

    public function modificarDesdeRequest(Request $request){
       //Por implementar
    }

    public function eliminar($id){
        $transaccionPago=$this->obtenerTransaccionPagoPorId($id);
        if ( is_null($transaccionPago) ){
            App::abort(404);
        }
        $transaccionPago->estado='0';
        $transaccionPago->update();
        return $transaccionPago;
    }

}//fin clase