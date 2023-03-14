
<div class="row">

    <input type="hidden" id="persona_id" name="persona_id">

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="doc_id" class="my-0"><strong>Num. Doc. Id.:</strong></label>
            <input type="text" name="doc_id" id="doc_id" required class="form-control" placeholder="Num. Doc. Id.">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="tipo_doc_id" class="my-0" ><strong>Tipo Documento Id.:</strong></label>
            <select name="tipo_doc_id" id="tipo_doc_id" required class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($tipo_docs as $tip)
                    <option value="{{$tip->id}}">{{$tip->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="nombre" class="my-0"><strong>Nombre:</strong></label>
            <input type="text" name="nombre" id="nombre" required class="form-control" placeholder="Nombre">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="paterno" class="my-0"><strong>Ap. Paterno:</strong></label>
            <input type="text" name="paterno" id="paterno" required class="form-control" placeholder="Ap. Paterno">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="materno" class="my-0"><strong>Ap. Materno:</strong></label>
            <input type="text" name="materno" id="materno" class="form-control" placeholder="Ap. Materno">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="sexo_id" class="my-0" ><strong>Sexo:</strong></label>
            <select name="sexo_id" id="sexo_id" required class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($sexos as $sex)
                    <option value="{{$sex->id}}">{{$sex->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group ">
            <label for="fecha_nac" class="my-0"><strong>Fecha Nacimiento:</strong></label>
            {{--  usando operador ternario php (?) para validar fecha nula, si es nulo muestra vacio... si no uso operador ternario muestra fecha actual en lugar de vacio --}}
            <input type="text" name="fecha_nac" id="fecha_nac" data-target="#fecha_nac"
                    data-toggle="datetimepicker" class="form-control datetimepicker-input datetimepicker_calendario"
                    required placeholder="Fecha de Nacimiento">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="estado_civil_id" class="my-0" ><strong>Estado Civil:</strong></label>
            <select name="estado_civil_id" id="estado_civil_id" class="form-control selectpicker border" data-live-search="true" >
                <option value="">--Seleccione--</option>
                @foreach($estados_civiles as $est_civ)
                    <option value="{{$est_civ->id}}"> {{$est_civ->nombre}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="email" class="my-0"><strong>Email Personal:</strong></label>
            <input type="text" name="email" id="email" required class="form-control" placeholder="email">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="telefono" class="my-0"><strong>Telefono Personal:</strong></label>
            <input type="text" name="telefono" id="telefono" required class="form-control" placeholder="Telefono">
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
        <div class="form-group">
            <label for="direccion" class="my-0"><strong>Dirección:</strong></label>
            <input type="text" name="direccion" id="direccion" required class="form-control" placeholder="Dirección">
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#doc_id').on('blur keypress',function(e){
                // se ejecuta al salir del cuadro de texto
                if (e.type == 'blur' || e.keyCode == 13) {
                    if($("#edit").val()==''){
                        buscarPorNumDocId($(this).val());
                    }
                }
            });
        });

        //BUSQUEDA POR DOCUMENTO DE IDENTIDAD
        function buscarPorNumDocId(num_doc_id){
            $.ajax({
                type: "GET",
                url: "{{route('buscarPersonaDocId')}}",
                data:{doc_id:num_doc_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){

                },//End success
                complete:function(result, textStatus ){
                    var data=result.responseJSON;
                    if(data.response){
                        messageAlert("El cliente con el numro de documento " + num_doc_id + ", ya esta registrado");
                        $("#edit").val("modificar");
                        $("#title_modal_view_cliente").text("MODIFICAR CLIENTE");
                        //Datos persona
                        $('#doc_id').val(data.persona.doc_id);
                        $('#tipo_doc_id').selectpicker('val',data.persona.tipo_doc_id);
                        $("#tipo_doc_id").selectpicker('refresh');
                        $('#nombre').val(data.persona.nombre);
                        $('#paterno').val(data.persona.paterno);
                        $('#materno').val(data.persona.materno);
                        $('#sexo_id').selectpicker('val',data.persona.sexo_id);
                        $("#sexo_id").selectpicker('refresh');
                        $('#fecha_nac').val( formatFecha(data.persona.fecha_nac));
                        $('#estado_civil_id').selectpicker('val',data.persona.estado_civil_id);
                        $("#estado_civil_id").selectpicker('refresh');
                        $('#email').val(data.persona.email);
                        $('#telefono').val(data.persona.telefono);
                        $('#direccion').val(data.persona.direccion);

                        //Datos cliente
                        $('#cliente_id').val(data.persona.id);
                        $('#pais_id').selectpicker('val',data.persona.pais_id);
                        $("#pais_id").selectpicker('refresh');
                        $('#profesion_id').selectpicker('val',data.persona.profesion_id);
                        $("#profesion_id").selectpicker('refresh');
                        $('#empresa_id').selectpicker('val',data.persona.empresa_id);
                        $("#empresa_id").selectpicker('refresh');
                        $('#detalle').val(data.persona.detalle);
                        $('#ciudad_id').selectpicker('val',data.persona.ciudad_id);
                        $("#ciudad_id").selectpicker('refresh');
                    } else {
                        $("#edit").val("");
                        //$("#title_modal_view_cliente").text("NUEVO CLIENTE");
                        limpiarDatoCliente();
                    }
                }
            });//End Ajax

        }//fin funcion

    </script>
@endpush


