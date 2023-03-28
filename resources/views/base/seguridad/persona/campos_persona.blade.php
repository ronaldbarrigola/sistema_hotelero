{{-- $url_create_edit puede venir a ser la url de usuario, vendedor, otro tipo de registro que incluya persona --}}
<input type="hidden" id="url_create_edit" name="id" value="{{$url_create_edit}}">
<input type="hidden" id="persona_id" name="id" value="{{$persona!=null?$persona->id:''}}">
{{--si entra a editar o crear persona, el estado siempre sera =1 (habilitado)--}}
<input type="hidden" id="estado" name="estado" value="1">
<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="doc_id" class="my-0"><strong>Num. Doc. Id.:</strong></label>
            <input type="text" name="doc_id" id="doc_id" required value="{{$persona!=null?$persona->doc_id:''}}" class="form-control" placeholder="Num. Doc. Id.">
            <div class="valid-feedback">Muy bien!</div>
            <div class="invalid-feedback">Ingrese número de documento de identidad!</div>
        </div>
    </div>

    <div class="panel_expedido col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="ciudad_exp_id" class="my-0" ><strong>Expedido:</strong></label>
            <select name="ciudad_exp_id" id="ciudad_exp_id" required class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($ciudades as $ciu)
                    <option value="{{$ciu->id}}" {{$persona!=null && $persona->ciudad_exp_id == $ciu->id ? 'selected' : '' }}> {{$ciu->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="tipo_doc_id" class="my-0" ><strong>Tipo Documento Id.:</strong></label>
            <select name="tipo_doc_id" id="tipo_doc_id" required class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($tipo_docs as $tip)
                    <option value="{{$tip->id}}" {{$persona!=null && $persona->tipo_doc_id == $tip->id ?'selected':''}}>{{$tip->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="nombre" class="my-0"><strong>Nombre:</strong></label>
            <input type="text" name="nombre" id="nombre" required value="{{$persona!=null?$persona->nombre:''}}" class="form-control" placeholder="Nombre">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="paterno" class="my-0"><strong>Ap. Paterno:</strong></label>
            <input type="text" name="paterno" id="paterno" required value="{{$persona!=null?$persona->paterno:''}}" class="form-control" placeholder="Ap. Paterno">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="materno" class="my-0"><strong>Ap. Materno:</strong></label>
            <input type="text" name="materno" id="materno" required value="{{$persona!=null?$persona->materno:''}}" class="form-control" placeholder="Ap. Materno">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="sexo_id" class="my-0" ><strong>Sexo:</strong></label>
            <select name="sexo_id" id="sexo_id" required class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($sexos as $sex)
                    <option value="{{$sex->id}}" {{$persona!=null && $persona->sexo_id == $sex->id ? 'selected' : '' }} >{{$sex->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group ">
            <label for="fecha_nac" class="my-0"><strong>Fecha Nacimiento:</strong></label>
            <input type="date" id="fecha_nac" name="fecha_nac" value="{{$persona!=null?$persona->fecha_nac:''}}" required class="form-control">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="estado_civil_id" class="my-0" ><strong>Estado Civil:</strong></label>
            <select name="estado_civil_id" id="estado_civil_id" class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($estados_civiles as $est_civ)
                    <option value="{{$est_civ->id}}" {{$persona!=null && $persona->estado_civil_id == $est_civ->id ? 'selected' : '' }}> {{$est_civ->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="email" class="my-0"><strong>Email Personal:</strong></label>
            <input type="text" name="email" id="email" required value="{{$persona!=null?$persona->email:''}}" class="form-control" placeholder="email">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="telefono" class="my-0"><strong>Telefono Personal:</strong></label>
            <input type="text" name="telefono" id="telefono" required value="{{$persona!=null?$persona->telefono:''}}" class="form-control" placeholder="Telefono">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="direccion" class="my-0"><strong>Dirección:</strong></label>
            <input type="text" name="direccion" id="direccion" required value="{{$persona!=null?$persona->direccion:''}}" class="form-control" placeholder="Dirección">
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function(){
            $('#doc_id').on('blur keypress',function(e){
                // se ejecuta al salir del cuadro de texto
                if (e.type == 'blur' || e.keyCode == 13) {
                    if($("#persona_id").val()==''){
                        buscarPorNumDocId($(this).val());
                    }
                }
            });
        });

        //BUSQUEDA POR DOCUMENTO DE IDENTIDAD
        function buscarPorNumDocId(num_doc_id){
            $.ajax({
                url:URL_BASE+"/seguridad/personas/buscar_por_num_doc_id",
                type:"get",
                data:"doc_id="+num_doc_id,
                datatype:"json",
                success:function(datosJSON, settings){


                    if(datosJSON.length>0){
                        boot4.confirm({
                            msg: 'El número de documento de identidad <span class="text-danger">'+num_doc_id+'</span>\nya fue registrado a nombre de:\n'+
                                    '<span class="text-danger">'+datosJSON[0].nombre+' '+datosJSON[0].paterno+ '</span>.  Desea Cargar los datos?',
                            title: "Confirmación",
                            callback: function(result) {
                                if(result){
                                    window.location.replace(URL_BASE+"/"+$("#url_create_edit").val()+"/"+datosJSON[0].id);
                                }
                                else{
                                    //console.log("cancel");
                                }
                            }//callback
                        });//confirm

                    }//FIN IF


                },
                error:function(){
                    //$("#mensajeErr").html("error");
                    console.error('error en llamada ajax');
                }
            });
        }//fin funcion
    </script>
@endpush
{{-- @include('base/personaRoles/adicionar',array('persona_id' => $persona->id)) --}}


<!--SCRITPS PARA MOSTRAR CONTROL FECHA -->
{{-- @include('partials/datetimepicker') --}}
{{-- FORMULARIO MODAL PARA ADICIONAR ROLES --}}


