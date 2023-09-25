<?php

namespace App\Repositories\Business;
use Illuminate\Support\Facades\Auth;
use App\Entidades\Business\Importe;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ImporteRepository{

    public function obtenerImportes(){
        $importes=DB::table('con_importe as i')
        ->leftjoin('con_forma_pago as f','f.id','=','i.forma_pago_id')
        ->select('i.id',DB::raw('DATE_FORMAT(i.fecha,"%d/%m/%Y") as fecha'),'f.descripcion as forma_pago','i.monto')
        ->where('i.estado','=','1')
        ->orderBy('i.id','desc')
        ->get();
        return  $importes;
    }

    public function obtenerImportePorPagoId($id){
        return Importe::where("pago_id",$id)->where("estado",1)->first();
    }

    public function obtenerImportePorId($id){
        return Importe::find($id);
    }

    public function insertarDesdeRequest(Request $request){
        $importe=null;
        $pago_id = $request->get('pago_id');
        $forma_pago_id=($request->get('forma_pago_id')!=null)?$request->get('forma_pago_id'):"E";

        if($forma_pago_id=="PM"){//Insercion multiple
            $vec_forma_pago_id=$request['fp_forma_pago_id'];
            $vec_concepto=$request['fp_concepto'];
            $vec_monto=$request['fp_monto'];
            $index=0;
            foreach ($vec_forma_pago_id as $row) {
                $forma_pago_id=($vec_forma_pago_id[$index]!=null)?$vec_forma_pago_id[$index]:0;
                $concepto=($vec_concepto[$index]!=null)?$vec_concepto[$index]:"";
                $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;
                $importe=new Importe();
                $importe->pago_id=$pago_id;
                $importe->forma_pago_id=$forma_pago_id;
                $importe->concepto=$concepto;
                $importe->monto=$monto;
                $importe->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
                $importe->usuario_alta_id=Auth::user()->id;
                $importe->usuario_modif_id=Auth::user()->id;
                $importe->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $importe->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $importe->estado=1;
                $importe->save();
                $index++;
            }
        } else { //Insercion individual
            $importe=new Importe();
            $concepto=$request['concepto'];
            $concepto=($concepto=null)?$concepto:"";
            $monto=$request['monto'];
            $monto=($monto!=null)?$monto:0;

            $importe->pago_id=$pago_id;
            $importe->forma_pago_id=$forma_pago_id;
            $importe->concepto=$concepto;
            $importe->monto=$monto;
            $importe->fecha=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->usuario_alta_id=Auth::user()->id;
            $importe->usuario_modif_id=Auth::user()->id;
            $importe->fecha_creacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->estado=1;
            $importe->save();
        }

        return $importe;
    }

    public function modificarDesdeRequest(Request $request){
        //variable
        $vec_importe_id=$request['fp_importe_id'];
        $vec_forma_pago_id=$request['fp_forma_pago_id'];
        $vec_monto=$request['fp_monto'];
        $index=0;
        foreach ($vec_forma_pago_id as $row) {
            $importe= $this->obtenerImportePorId($vec_importe_id[$index]);
            if($importe!=null){
                $monto=($vec_monto[$index]!=null)?$vec_monto[$index]:0;
                $forma_pago_id=($vec_forma_pago_id[$index]!=null)?$vec_forma_pago_id[$index]:0;
                $importe->forma_pago_id=$forma_pago_id;
                $importe->monto=$monto;
                $importe->usuario_modif_id=Auth::user()->id;
                $importe->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
                $importe->update();
            }

            $index++;
        }
    }

    public function modificarImportePorPagoId(Request $request){
        $pago_id = $request->get('pago_id');
        $monto = $request->get('monto');
        $forma_pago_id=($request->get('forma_pago_id')!=null)?$request->get('forma_pago_id'):"E";
        $importe=$this->obtenerImportePorPagoId($pago_id);
        if($importe!=null){
            $importe->forma_pago_id=$forma_pago_id;
            $importe->monto=$monto;
            $importe->usuario_modif_id=Auth::user()->id;
            $importe->fecha_modificacion=Carbon::now('America/La_Paz')->toDateTimeString();
            $importe->update();
        }
    }

    public function eliminar($id){
        $importe=$this->obtenerImportePorId($id);
        $importe->delete();
        return $importe;
    }

}//fin clase
