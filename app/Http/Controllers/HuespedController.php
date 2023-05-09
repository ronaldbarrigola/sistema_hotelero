<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\HuespedRepository;
use App\Repositories\Base\PersonaRepository;


class HuespedController extends Controller
{
    protected $huespedRep;
    protected $personaRep;

    public function __construct(PersonaRepository $personaRep,HuespedRepository $huespedRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->huespedRep=$huespedRep;
        $this->personaRep=$personaRep;
    }

    public function index(Request $request){
        $reserva_id=($request['reserva_id']!=null)?$request['reserva_id']:0;
        return $this->huespedRep->obtenerHuespedesDataTables($reserva_id);
    }

    public function create(){
       //Sin acciones
    }

    public function store(Request $request){
        $huesped=$this->huespedRep->insertarDesdeRequest($request);
        return response()->json(array ('huesped'=>$huesped));
    }

    public function edit(Request $request){
       //Sin acciones
    }

    public function update(Request $request, $id){
        $request->request->add(['huesped_id'=>$id]);
        $huesped=$this->huespedRep->modificarDesdeRequest($request);
        return  $huesped;
    }

    public function destroy(Request $request,$id){
        $huesped=$this->huespedRep->eliminar($id);
        if($huesped->ajax()){
             return response()->json(array (
                'msg'=>'huesped '.$huesped->id.', eliminada',
                'id'=>$huesped->id
            ));
        }
    }
}
