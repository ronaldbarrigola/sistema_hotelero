@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
    {{$sucursal!=null?'Modificar':'Crear'}} Sucursal
    @endsection

    @section('panelCuerpo')

        <form method="POST" action="{{url('/seguridad/sucursales').($sucursal!=null?'/'.$sucursal->id:'')}}" autocomplete="off" novalidate='novalidate' class='needs-validation'>
            @csrf
            @if ($sucursal!=null)
                @method("PATCH")
            @else
                @method("POST")
            @endif
            <input type="hidden" name="id" value={{$sucursal!=null?$sucursal->id:''}}>

            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="nombre" class="my-0"><strong>Nombre:</strong></label>
                        <input type="text" name="nombre" id="nombre" required value="{{$sucursal!=null?$sucursal->nombre:''}}" class="form-control" placeholder="Nombre">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="ciudad_id" class="my-0" ><strong>Ciudad:</strong></label>
                        <select name="ciudad_id" id="ciudad_id" required class="form-control selectpicker border" data-live-search="true" >
                            <option value="">--Seleccione--</option>
                            @foreach($ciudades as $ciu)
                                <option value="{{$ciu->id}}" {{$sucursal!=null && $sucursal->ciudad_id == $ciu->id ? 'selected' : '' }}> {{$ciu->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="observacion" class="my-0"><strong>Observación:</strong></label>
                        <input type="text" name="observacion" id="observacion" required value="{{$sucursal!=null?$sucursal->observacion:''}}" class="form-control" placeholder="Observación">
                    </div>
                </div>

            </div>

            {{-- @include('base/sucursalRoles/index', array('sucursal_id' => $sucursal->id)) --}}
            <div class="row">
                <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a class="btn btn-primary" href="{{route('sucursales.index')}}">Volver</a>
                </div>
            </div>
        </form>
    @endsection
@endsection


<!-- ESTILOS Y SCRIPTS -->

