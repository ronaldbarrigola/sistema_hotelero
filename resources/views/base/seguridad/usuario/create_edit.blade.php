@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @if ($persona!=null)
            <span class="font-weight-bold">Modificando Persona:</span><span class="text-warning">  {{$persona->id.' - '.$persona->nombre.' '.$persona->paterno}}</span>
        @else
            <span class="font-weight-bold">Crear Usuario:</span>
        @endif

    @endsection

    @section('panelCuerpo')
        <form id="frmUsuario" method="POST" action="{{url('/seguridad/usuarios').($persona!=null?'/'.$persona->id:'')}}" autocomplete="off"  novalidate='novalidate' class='needs-validation'>
            @csrf
            @if ($persona!=null)
                @method("PATCH")
            @else
                @method("POST")
            @endif

            @include('base/seguridad/persona/campos_persona', array('url_create_edit' =>'seguridad/usuarios/create_edit'))

            <div class="card">
                <div class="card-header py-0">
                    <strong>DATOS DE ACCESO AL SISTEMA</strong>
                </div>
                <div class="card-body row py-0">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label for="txtLogin" class="my-0"><strong>Login:</strong></label>
                            <input type="text" id="txtLogin"  name="login" required value="{{$persona!=null && $persona->usuario!=null?$persona->usuario->login:''}}" class="form-control" placeholder="Login">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label for="txtEmail" class="my-0"><strong>Email:</strong></label>
                            <input type="text" id="txtEmail" name="email" required value="{{$persona!=null && $persona->usuario!=null?$persona->usuario->email:''}}" class="form-control" placeholder="email">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label for="sucursal_id" class="my-0" ><strong>Sucursal:</strong></label>
                            <select name="sucursal_id" id="sucursal_id" required class="form-control selectpicker border" data-live-search="true" >
                                <option value="">--Seleccione--</option>
                                @foreach($sucursales as $suc)
                                    <option value="{{$suc->id}}" {{$persona!=null && $persona->usuario!=null && $persona->usuario->sucursal_id == $suc->id ? 'selected' : '' }} >{{$suc->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label for="agencia_id" class="my-0" ><strong>Agencia:</strong></label>
                            <select name="agencia_id" id="agencia_id" required class="form-control selectpicker border" data-live-search="true" >
                                <option value="">--Seleccione--</option>
                                <input type="hidden" id="agencia_id_aux" value="{{$persona!=null && $persona->usuario!=null?$persona->usuario->agencia_id:''}}">
                                {{-- @foreach($sucursales as $suc)
                                    <option value="{{$suc->id}}" {{$usuario!=null && $usuario->sucursal_id == $suc->id ? 'selected' : '' }} >{{$suc->nombre}}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                </div>
                @include('base/seguridad/usuarioRoles/index', array('usuario_id' =>$persona!=null && $persona->usuario!=null? $persona->id:null))
            </div>
        </form>

        <br>
        <div class="row">
            <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                <a class="btn btn-primary" href="javascript:history.back(-1);">Cancelar</a>
                <button class="btn btn-success " form="frmUsuario" id="bntGuardarUsuario">Guardar</button>
            </div>
        </div>

    @endsection
@endsection


<!-- ESTILOS Y SCRIPTS -->
@include('partials/datetimepicker')
@push('scripts')
        <script>
            $(document).ready(function(){
                agencia_id_aux=$("#agencia_id_aux").val();
                cargarComboAgencias(agencia_id_aux);
                //=====================================================================================================================
                //evento Select item Change
                //=====================================================================================================================
                $("#sucursal_id").on('change',function(){
                    //sucursal_id=this.value;
                    cargarComboAgencias('');
                });
            });

            //=====================================================================================================================
            // CARGAR AGENCIAS
            //=====================================================================================================================
            function cargarComboAgencias(val_agencia_sel){
                // $("#agencia_id").empty();
                $("#agencia_id option[value!='']").remove();//eliminando todas las opciones excepto el primer item "--seleccione--" cuyo valor es =''
                sucursal_id=$("#sucursal_id").val();

                $.ajax({
                    url:URL_BASE+"/seguridad/agencias/obtener_agencias_por_sucural/"+sucursal_id,
                    type:"get",
                    data:"",
                    datatype:"json",
                    success:function(datosJSON, settings){
                        $.each(datosJSON, function(i,item){
                            //FUNCIONA CON RENDER//seleccionado=agencia_id_aux==item.id?"selected":"";// en caso de edicion al cargar los datos, si el usuario ya tiene agencia, se selecciona
                            //FUNCIONA CON RENDER//$("#agencia_id").append('<option value="'+item.id+'" '+seleccionado+'>'+item.nombre+'</option>');
                            $("#agencia_id").append('<option value="'+item.id+'">'+item.nombre+'</option>');
                        });
                        $('#agencia_id').selectpicker('refresh');//actualiza el combobox (cuando "select" tiene la clase selectpicker no actualiza la lista por eso se ejecuta esta linea)
                        $('#agencia_id').selectpicker('val', val_agencia_sel);
                        //FUNCIONA CON RENDER//$('#agencia_id').selectpicker('render');//actualiza html de opciones, por ejemplo el estado selected de cada option

                    },
                    error:function(){
                        //$("#mensajeErr").html("error");
                    }
                });
            }//fin funcion
        </script>
@endpush
{{-- @include('base/personaRoles/adicionar',array('persona_id' => $persona->id)) --}}


<!--SCRITPS PARA MOSTRAR CONTROL FECHA -->
{{-- @include('partials/datetimepicker') --}}
{{-- FORMULARIO MODAL PARA ADICIONAR ROLES --}}


