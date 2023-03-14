<?php

namespace App\Http\Controllers;

use App\Repositories\Business\HabitacionRepository;



class HomeController extends Controller
{
    protected $habitacionRep;

    public function __construct(HabitacionRepository $habitacionRep)
    {
        $this->middleware('auth');
        $this->middleware('guest');
        $this->habitacionRep=$habitacionRep;
    }

    public function index()
    {
       //$habitaciones=$this->habitacionRep->obtenerHabitaciones();
       return view('home');
    }

}
