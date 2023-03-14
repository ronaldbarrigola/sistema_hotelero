@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        {{$agencia!=null?'Modificar':'Crear'}} Agencia
    @endsection

    @section('panelCuerpo')
        <div class="card p-0 ">
            <div class="card-header p-0 d-flex justify-content-center">
                SUCURSAL : <span class="text-warning">{{$sucursal->nombre}}</span>
            </div>
        </div>
        <form method="POST" action="{{url('/seguridad/agencias').($agencia!=null?'/'.$agencia->id:'')}}" autocomplete="off" novalidate='novalidate' class='needs-validation'>
            @csrf
            @if ($agencia!=null)
                @method("PATCH")
            @else
                @method("POST")
            @endif

            <input type="hidden" name="id" value={{$agencia!=null?$agencia->id:''}}>
            <input type="hidden" name="tipo_id" value="1">

            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="sucursal_id" class="my-0" ><strong>Sucursal:</strong></label>
                        <select name="sucursal_id" id="sucursal_id" required class="form-control selectpicker border" data-live-search="true" >
                            <option value="">--Seleccione--</option>
                            @foreach($lista_sucursales as $suc)
                                @if($agencia!=null)
                                    <option value="{{$suc->id}}" {{$agencia->sucursal_id == $suc->id ? 'selected' : 'disabled' }}> {{$suc->nombre}}</option>
                                @else
                                    <option value="{{$suc->id}}" {{$sucursal->id == $suc->id ? 'selected' : 'disabled' }}> {{$suc->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="nombre" class="my-0"><strong>Nombre Agencia:</strong></label>
                        <input type="text" name="nombre" id="nombre" required value="{{$agencia!=null?$agencia->nombre:''}}" class="form-control" placeholder="Nombre">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="direccion" class="my-0"><strong>Direccion:</strong></label>
                        <input type="text" name="direccion" id="direccion" required value="{{$agencia!=null?$agencia->direccion:''}}" class="form-control" placeholder="Direccion">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="fono" class="my-0"><strong>Telefono:</strong></label>
                        <input type="text" name="fono" id="fono" required value="{{$agencia!=null?$agencia->fono:''}}" class="form-control" placeholder="Telefono">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="observacion" class="my-0"><strong>Observación:</strong></label>
                        <input type="text" name="observacion" id="observacion" required value="{{$agencia!=null?$agencia->observacion:''}}" class="form-control" placeholder="Observación">
                    </div>
                </div>

            </div>

            {{-- @include('base/agenciaRoles/index', array('agencia_id' => $agencia->id)) --}}
            <div class="row">
                <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <a class="btn btn-primary" href="javascript:history.back(-1);">Volver</a>
                </div>
            </div>
        </form>
    @endsection
@endsection


<!-- ESTILOS Y SCRIPTS -->

