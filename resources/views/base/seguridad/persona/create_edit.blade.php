@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
    {{$persona!=null?'Modificar':'Crear'}} Persona
    @endsection

    @section('panelCuerpo')

        <form method="POST" action="{{url('/seguridad/personas').($persona!=null?'/'.$persona->id:'')}}" autocomplete="off" novalidate='novalidate' class='needs-validation'>
            @csrf
            @if ($persona!=null)
                @method("PATCH")
            @else
                @method("POST")
            @endif

            @include('base/seguridad/persona/campos_persona', array('url_create_edit' =>'seguridad/personas/create_edit'))

            {{-- @include('base/personaRoles/index', array('persona_id' => $persona->id)) --}}
            <div class="row">
                <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a class="btn btn-primary" href="{{route('personas.index')}}">Volver</a>
                </div>
            </div>
        </form>
    @endsection
@endsection


<!-- ESTILOS Y SCRIPTS -->
@include('partials/datetimepicker')

{{-- @include('base/personaRoles/adicionar',array('persona_id' => $persona->id)) --}}
