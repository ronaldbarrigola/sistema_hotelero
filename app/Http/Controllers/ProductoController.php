<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\Business\CategoriaRepository;
use App\Repositories\Business\ProductoRepository;

class ProductoController extends Controller
{
    protected $categoriaRep;
    protected $productoRep;

    public function __construct(CategoriaRepository $categoriaRep,ProductoRepository $productoRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->categoriaRep=$categoriaRep;
        $this->productoRep=$productoRep;
    }

     public function index(Request $request){
        if($request->ajax()){
            return $this->productoRep->obtenerProductoDataTables();
        }else{
            $categorias=$this->categoriaRep->obtenerCategorias();
            return view('business.producto.index',['categorias'=>$categorias]);
        }
    }

    public function store(Request $request){
        $producto=$this->productoRep->insertarDesdeRequest($request);
        return response()->json(array ('producto'=>$producto));
    }

    public function edit(Request $request){
        $id=$request['producto_id'];
        $producto=$this->productoRep->obtenerProductoPorId($id);
        return response()->json(array ('producto'=>$producto));
    }

    public function update(Request $request, $id){
        $request->request->add(['id'=>$id]);
        $prodcuto=$this->productoRep->modificarDesdeRequest($request);
        return  $prodcuto;
    }

    public function destroy(Request $request,$id){
        $producto=$this->productoRep->eliminar($id);
        if($request->ajax()){
             return response()->json(array (
                'msg'     => 'producto '.$producto->descripcion.', eliminada',
                'id'      => $producto->id
            ));
        }
        return Redirect::route('business.producto.index');
    }
}
