<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Business\ClienteCiudadRepository;
use App\Repositories\Business\PaisRepository;
use Carbon\Carbon;

class ClienteCiudadController extends Controller
{
    protected $ciudadRep;
    protected $paisRep;

    //===constructor=============================================================================================
    public function __construct(ClienteCiudadRepository $ciudadRep,PaisRepository $paisRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->ciudadRep=$ciudadRep;
        $this->paisRep=$paisRep;
    }

    //================================================================================================
    public function obtenerCiudadesPorPaisId(Request $request){
        $id=$request['pais_id'];
        $ciudades=$this->ciudadRep->obtenerCiudadesPorPaisId($id);
        return $ciudades;
    }

    public function index(Request $request){
        if($request->ajax()){
           return $this->ciudadRep->obtenerCiudadesDataTables();
        }else{
           $paises=$this->paisRep->obtenerPaises();
           return view('business.ciudad.index',["paises"=>$paises]);
        }
    }

    public function store(Request $request){
        $ciudad=$this->ciudadRep->insertarDesdeRequest($request); //Desde el repository retorna json
        return $ciudad;
    }

    public function edit(Request $request){
        $id=$request['ciudad_id'];
        $ciudad=$this->ciudadRep->obtenerCiudadPorId($id);
        return response()->json(array ('ciudad'=>$ciudad));
    }

    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $ciudad=$this->ciudadRep->modificarDesdeRequest($request);
        return response()->json(array ('ciudad'=>$ciudad));
    }

    public function destroy($id){
        $ciudad=$this->ciudadRep->eliminar($id);
        return response()->json(array ('ciudad'=>$ciudad));
    }

}
