<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\Business\ProfesionRepository;

class ProfesionController extends Controller
{
    protected $profesionRep;

    public function __construct(ProfesionRepository $profesionRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->profesionRep=$profesionRep;
    }

    public function index(Request $request){
        if($request->ajax()){
           return $this->profesionRep->obtenerProfesionesDataTables();
        }else{
           return view('business.profesion.index');
        }
    }

    public function obtenerProfesiones(){
        $profesiones=$this->profesionRep->obtenerProfesiones();
        return response()->json(array ('profesiones'=>$profesiones));
    }

    public function store(Request $request){
        $profesion=$this->profesionRep->insertarDesdeRequest($request);
        return $profesion;
    }

    public function edit(Request $request){
        $id=$request['profesion_id'];
        $profesion=$this->profesionRep->obtenerProfesionPorId($id);
        return response()->json(array ('profesion'=>$profesion));
    }

    public function update(Request $request,$id){
        $request->request->add(['id'=>$id]);
        $profesion=$this->profesionRep->modificarDesdeRequest($request);
        return response()->json(array ('profesion'=>$profesion));
    }

    public function destroy($id){
        $profesion=$this->profesionRep->eliminar($id);
        return response()->json(array ('profesion'=>$profesion));
    }


}
