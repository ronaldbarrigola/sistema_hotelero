<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\PaisRepository;

class PaisController extends Controller
{
    protected $paisRep;

    public function __construct(PaisRepository $paisRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->paisRep=$paisRep;
    }

    public function index(Request $request){
        if($request->ajax()){
           return $this->paisRep->obtenerPaisesDataTables();
        }else{
           return view('business.pais.index');
        }
    }

    public function store(Request $request){
        $paises=$this->paisRep->insertarDesdeRequest($request);
        return response()->json(array ('paises'=>$paises));
    }

    public function edit(Request $request){
        $id=$request['pais_id'];
        $pais=$this->paisRep->obtenerPaisPorId($id);
        return response()->json(array ('pais'=>$pais));
    }

    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $pais=$this->paisRep->modificarDesdeRequest($request);
        return response()->json(array ('pais'=>$pais));
    }

    public function destroy(Request $request,$id){
        $pais=$this->paisRep->eliminar($id);
        return response()->json(array ('pais'=>$pais));
    }

}
