<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\CategoriaRepository;

class CategoriaController extends Controller
{
    protected $categoriaRep;
    //===constructor=============================================================================================
    public function __construct(CategoriaRepository $categoriaRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->categoriaRep=$categoriaRep;
    }

     //===========================================================================================================
     public function index(Request $request){
        if($request->ajax()){
            return $this->categoriaRep->obtenerCategoriaDataTables();
        }else{
            return view('business.categoria.index');
        }
    }

    //================================================================================================
    public function create(){
        return view('business.categoria.create');
    }

    //================================================================================================
    public function store(Request $request){
        $categoria=$this->categoriaRep->insertarDesdeRequest($request);
        if($request->ajax()){
           $listaCategoria=$this->categoriaRep->obtenerCategoria();
           return response()->json(array ('categoria'=>$categoria,'listacategoria'=>$listaCategoria));
        } else {
           return Redirect::to('business/categoria');//esto va al index
        }
    }

    //================================================================================================
    public function show($id){
        return Redirect::to('business/categoria?categoria_id='.$id);
    }

    //================================================================================================
    public function edit($id){
        $categoria=$this->categoriaRep->obtenerCategoriaPorId($id);
        return view('business.categoria.edit',['categoria'=>$categoria]);
    }

    //================================================================================================
    public function update(Request $request, $id){
        $request['categoria_id']=$id;//ADICIONANDO MANUALMENTE AL REQUEST EL ID. PARA NO ENVIAR COMO OTRO PARAMETRO.
        $this->categoriaRep->modificarDesdeRequest($request);
        return Redirect::to('business/categoria');//esto va al index
    }

   //================================================================================================
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
