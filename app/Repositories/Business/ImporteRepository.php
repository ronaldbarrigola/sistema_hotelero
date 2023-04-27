<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\Importe;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class TransaccionRepository{

    public function obtenerImportes(){
        $importes=DB::table('con_importe as i')
        ->leftjoin('con_forma_pago as f','f.id','=','i.forma_pago_id')
        ->select('i.id',DB::raw('DATE_FORMAT(i.fecha,"%d/%m/%Y") as fecha'),'f.descripcion as forma_pago','i.monto')
        ->where('i.estado','=','1')
        ->orderBy('i.id','desc')
        ->get();
        return  $importes;
    }

    public function obtenerImportePorId($id){
        return Importe::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $importe=null;
        $pago_id = $request->get('pago_id');
        $vec_forma_pago_id=$request['vec_forma_pago_id'];
        $vec_importe_id=$request['vec_importe_id'];
        $vec_monto=$request['vec_monto'];
        $index=0;
        foreach ($vec_importe_id as $row) {
            $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;
            $forma_pago_id=($vec_forma_pago_id[$index]!=null)?$vec_forma_pago_id[$index]:0;
            $importe=new Importe();
            $importe->pago_id=$pago_id;
            $importe->forma_pago_id=$forma_pago_id;
            $importe->monto=$monto;
            $importe->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->estado=1;
            $importe->save();
            $index++;
        }

        return $importe;
    }

    public function modificarDesdeRequest(Request $request){
        //variable
        $vec_importe_id=$request['vec_importe_id'];
        $vec_forma_pago_id=$request['vec_forma_pago_id'];
        $vec_monto=$request['vec_monto'];
        $index=0;
        foreach ($vec_importe_id as $row) {
            $importe= $this->obtenerImportePorId($vec_importe_id[$index]);
            if($importe!=null){
                $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;
                $forma_pago_id=($vec_forma_pago_id[$index]!=null)?$vec_forma_pago_id[$index]:0;
                $importe->forma_pago_id=$forma_pago_id;
                $importe->monto=$monto;
                $importe->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $importe->update();
            }

            $index++;
        }

    }

    public function eliminar($id){
        $importe=$this->obtenerImportePorId($id);
        if ( is_null($importe) ){
            App::abort(404);
        }
        $importe->delete();
        return $importe;
    }

}//fin clase
