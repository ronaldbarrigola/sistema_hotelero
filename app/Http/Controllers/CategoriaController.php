<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\CategoriaRepository;

class CategoriaController extends Controller
{
    protected $categoriaRep;

    public function __construct(CategoriaRepository $categoriaRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->categoriaRep=$categoriaRep;
    }

     public function index(Request $request){
        if($request->ajax()){
            return $this->categoriaRep->obtenerCategoriaDataTables();
        }else{
            return view('business.categoria.index');
        }
    }

    public function create(){
        return view('business.categoria.create');
    }

    public function store(Request $request){
        $categoria=$this->categoriaRep->insertarDesdeRequest($request);
        return response()->json(array ('categoria'=>$categoria));
    }

    public function edit(Request $request){
        $id=$request['categoria_id'];//El mismo id se usa mapra persona y cliente
        $categoria=$this->categoriaRep->obtenerCategoriaPorId($id);
        return response()->json(array ('categoria'=>$categoria));
    }

    public function update(Request $request, $id){
        $request->request->add(['id'=>$id]);//El mismo id se usa mapra persona y cliente
        $categoria=$this->categoriaRep->modificarDesdeRequest($request);
        return  $categoria;
    }

    public function destroy(Request $request,$id){
        $categoria=$this->categoriaRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'categoria '.$categoria->categoria. ', eliminada',
                'id'      => $categoria->categoria_id
            ));
        }

        return Redirect::route('business.categoria.index');
    }
}
