<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Repositories\Base\CiudadRepository;
use Carbon\Carbon;

class CiudadController extends Controller
{
    protected $ciudadRep;

    //===constructor=============================================================================================
    public function __construct(CiudadRepository $ciudadRep){
        $this->middleware('auth');
        $this->middleware('guest');
        $this->ciudadRep=$ciudadRep;
    }

    //================================================================================================
    public function obtenerCiudadesPorPaisId(Request $request){
        $id=$request['pais_id'];
        $ciudades=$this->ciudadRep->obtenerCiudadesPorPaisId($id);
        return $ciudades;
    }

}
