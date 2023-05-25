<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Business\DatoFacturaRepository;

class DatoFacturaController extends Controller
{
    protected $datoFacturaRep;

    public function __construct(DatoFacturaRepository $datoFacturaRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->datoFacturaRep=$datoFacturaRep;
    }

    public function obtenerDatoFacturaPorNit(Request $request){
        $response=false;
        $nit=($request["nit"]!=null)?$request["nit"]:"";
        $datoFactura=$this->datoFacturaRep->obtenerDatoFacturaPorNit($nit);
        if($datoFactura!=null){
            $response=true;
        }

        return response()->json(array ('response'=>$response,'datofactura'=>$datoFactura));
    }

}
