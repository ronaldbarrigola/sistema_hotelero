@extends('layouts.plantilla')
@section('content')
        <div class="col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-header">SELECCIONE ROL</div>

                        <div class="card-body">
                            <p>Bienvenido {{ Auth::user()->nombre }}!! usted cuenta con {{Auth::user()->roles->count()}} Roles, seleccione uno:</p>
                            <br>

                            @foreach(Auth::user()->roles as $rol)
                                {{-- {{Form::Open(array('url'=>'/seguridad/roles/ingresar','method'=>'POST','autocomplete'=>'off'))}} --}}
                                <form method="POST" action="{{url('/seguridad/roles/ingresar')}}" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="idrol" value="{{$rol->id}}">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{$rol->nombre}}</button>
                                </form>
                                {{-- {{Form::Close()}} --}}
                                <br>
                            @endforeach
                        </div>
                    </div>
        </div>
 @endsection
