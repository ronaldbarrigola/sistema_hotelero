<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewCliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_cliente" class="modal-title">INFORMACION CLIENTE</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmCliente" enctype="multipart/form-data" onsubmit="return submitFormCliente(event)">
                    @csrf

                    <input type="hidden" name="editCliente" id="editCliente" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS PERSONA</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/cliente/campos_persona')
                        </div>
                    </div>

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS CLIENTE</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/cliente/campos_cliente')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarCliente" type="submit">Guardar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer">

            </div>

        </div>
    </div>
</div> <!--End Modal-->

@push('scripts')
  <script>
        $(document).ready(function() {

          $(document).on("change", "#pais_id", function(){
              obtenerCiudades();
          });

          $('#modalViewCliente').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
              $("#doc_id").focus();
          })

          $(document).on("click", "#btnModalCreateProfesion", function(){
                createProfesion(false);
          });

        }); //Fin ready

        function submitFormCliente(event) {
            storeCliente();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function storeCliente(){
            var formdata = new FormData($("#frmCliente")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/cliente";

            if($("#editCliente").val()=="modificar"){
               url= url + "/" + $("#persona_id").val();
               formdata.append('_method','patch');
            }

            $.ajax({
                type: "POST",
                processData: false, //importante para enviar imagen
                contentType: false, //importante para enviar imagen
                enctype: 'multipart/form-data', //importante para enviar imagen
                url:url,
                data:formdata,
                dataType: 'json',
                beforeSend: function () {
                    $("#btnGuardarCliente").attr('disabled','disabled');
                    $("#btnGuardarCliente").html("Procesando");
                },
                success: function(result){
                    //BEGIN:Es para cuando se hace el llamado al formulario modal desde otro modulo
                    $("#cliente_id").find('option').remove();
                    $("#huesped_cliente_id").find('option').remove();
                    $.each(result.clientes , function(i, v) {
                        $("#cliente_id").append('<option value="' + v.id + '" data-nombre="' + v.nombre + '" data-paterno="' + v.paterno + '" data-materno="' + v.materno + '" data-nro_doc="' + v.doc_id + '" data-tipo_doc="' + v.tipo_documento + '">' + v.cliente + " " + v.doc_id + '</option>');
                        $("#huesped_cliente_id").append('<option value="' + v.id + '" data-nombre="' + v.nombre + '" data-paterno="' + v.paterno + '" data-materno="' + v.materno + '" data-nro_doc="' + v.doc_id + '" data-tipo_doc="' + v.tipo_documento + '">' + v.cliente + " " + v.doc_id + '</option>');
                    });
                    $("#cliente_id").selectpicker('refresh');
                    $("#cliente_id").selectpicker('val', result.cliente.id);
                    $("#cliente_id").selectpicker('refresh');

                    $("#huesped_cliente_id").selectpicker('refresh');
                    $("#huesped_cliente_id").selectpicker('val', result.cliente.id);
                    $("#huesped_cliente_id").selectpicker('refresh');
                    //END:Es para cuando se hace el llamado al formulario modal desde otro modulo

                    $("#modalViewCliente").modal("hide");
                    $("#btnGuardarCliente").removeAttr('disabled');
                    $("#btnGuardarCliente").html("Guardar");
                    $("#doc_id").val("");
                    limpiarDatoCliente();


                    try {
                        datatable_datos.ajax.reload();//recargar registro datatables.
                    }
                    catch(err) {
                      //En caso de que se cree la reserva desde el TimeLines
                    }

                    try {
                       cargarFilaHuesped(result.persona.id,result.persona.nombre,result.persona.paterno,result.persona.materno,result.persona.doc_id,result.tipo_documento.nombre) //Se encuentra modulo huesped detalle_huesped
                       $("#huesped_cliente_id").selectpicker('val','');
                       $("#huesped_cliente_id").selectpicker('refresh');
                    }
                    catch(err) {
                      //En caso de que se cree la reserva desde el TimeLines
                    }

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            });//End Ajax
       }

       function createCliente(){
            $("#editCliente").val("");
            $("#title_modal_view_cliente").text("NUEVO CLIENTE");
            $("#doc_id").removeAttr('readonly');
            $("#doc_id").val("");
            limpiarDatoCliente();

            $(".persona_natural").show();
            setRequiredPersonaNatutal(true);

            $.ajax({
                type: "GET",
                url: "{{route('createcliente')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataClienteAjax(result);
                    $('#tipo_persona_id').selectpicker('val','N');
                    $("#tipo_persona_id").selectpicker('refresh');
                    $('#modalViewCliente').modal('show');
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

       function editCliente($boton){
            limpiarDatoCliente();
            var persona_id=$boton.id;
            $("#editCliente").val("modificar");
            $("#title_modal_view_cliente").text("MODIFICAR CLIENTE");
            $.ajax({
                type: "GET",
                url: "{{route('editcliente')}}",
                data:{persona_id:persona_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataClienteAjax(result);
                    if(result.persona.tipo_persona_id=="J"){
                        $(".persona_natural").hide();
                        setRequiredPersonaNatutal(false);
                    } else {
                        $(".persona_natural").show();
                        setRequiredPersonaNatutal(true);
                        $('#paterno').val(result.persona.paterno);
                        $('#materno').val(result.persona.materno);
                        $('#sexo_id').selectpicker('val', result.persona.sexo_id);
                        $("#sexo_id").selectpicker('refresh');
                        $('#fecha_nac').val(result.persona.fecha_nac);
                        $('#estado_civil_id').selectpicker('val', result.persona.estado_civil_id);
                        $("#estado_civil_id").selectpicker('refresh');
                        $('#profesion_id').selectpicker('val', result.cliente.profesion_id);
                        $("#profesion_id").selectpicker('refresh');
                        $('#empresa_id').selectpicker('val', result.cliente.empresa_id);
                        $("#empresa_id").selectpicker('refresh');
                    }
                    //Datos persona
                    $('#persona_id').val(result.persona.id);
                    $('#nombre').val(result.persona.nombre);
                    $('#doc_id').val(result.persona.doc_id);
                    $('#tipo_doc_id').selectpicker('val', result.persona.tipo_doc_id);
                    $("#tipo_doc_id").selectpicker('refresh');
                    $('#tipo_persona_id').selectpicker('val',result.persona.tipo_persona_id);
                    $("#tipo_persona_id").selectpicker('refresh');

                    $('#email').val(result.persona.email);
                    $('#telefono').val(result.persona.telefono);
                    $('#direccion').val(result.persona.direccion);

                    //Datos cliente
                    $('#pais_id').selectpicker('val', result.cliente.pais_id);
                    $("#pais_id").selectpicker('refresh');


                    $("#ciudad_id").find('option').remove();
                    $.each( result.ciudades , function(i, v) {
                        $("#ciudad_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });

                    $("#ciudad_id").selectpicker('refresh');
                    $('#ciudad_id').selectpicker('val', result.cliente.ciudad_id);
                    $("#ciudad_id").selectpicker('refresh');


                    $("#modalViewCliente").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function loadDataClienteAjax(result){
            $("#tipo_persona_id").find('option').remove();
            $.each(result.tipo_persona, function(i, v) {
                $("#tipo_persona_id").append('<option  value="' + v.id + '">' + v.descripcion + '</option>');
            });
            $("#tipo_persona_id").selectpicker('refresh');

            $("#tipo_doc_id").find('option').remove();
            $("#tipo_doc_id").append('<option  value="" selected>Seleccione</option>');
            $.each(result.tipo_docs, function(i, v) {
                $("#tipo_doc_id").append('<option  value="' + v.id + '" >' + v.nombre + '</option>');
            });
            $("#tipo_doc_id").selectpicker('refresh');

            $("#sexo_id").find('option').remove();
            $("#sexo_id").append('<option  value="" selected>Seleccione</option>');
            $.each(result.sexos, function(i, v) {
                $("#sexo_id").append('<option  value="' + v.id + '" >' + v.nombre + '</option>');
            });
            $("#sexo_id").selectpicker('refresh');

            $("#estado_civil_id").find('option').remove();
            $("#estado_civil_id").append('<option  value="" selected>Seleccione</option>');
            $.each(result.estados_civiles, function(i, v) {
                $("#estado_civil_id").append('<option  value="' + v.id + '" >' + v.nombre + '</option>');
            });
            $("#estado_civil_id").selectpicker('refresh');

            $("#pais_id").find('option').remove();
            $("#pais_id").append('<option  value="" selected>Seleccione</option>');
            $.each(result.paises, function(i, v) {
                $("#pais_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
            });
            $("#pais_id").selectpicker('refresh');

            $("#profesion_id").find('option').remove();
            $("#profesion_id").append('<option  value="" selected>Seleccione</option>');
            $.each(result.profesiones, function(i, v) {
                $("#profesion_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
            });
            $("#profesion_id").selectpicker('refresh');

            $("#empresa_id").find('option').remove();
            $("#empresa_id").append('<option  value="" selected>Seleccione</option>');
            $.each(result.empresas, function(i, v) {
                $("#empresa_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
            });
            $("#empresa_id").selectpicker('refresh');
        }

        function obtenerCiudades(){
            var pais_id= $("#pais_id").val();
            $.ajax({
                type: "get",
                url: "{{route('listaciudades')}}",
                data:{pais_id:pais_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(response){
                    $("#ciudad_id").find('option').remove();
                    $("#ciudad_id").append('<option value="">--Seleccione--</option>');
                    $.each( response , function(i, v) {
                        $("#ciudad_id").append('<option value="' + v.id + '">' + v.descripcion + '</option>');
                    });
                    $("#ciudad_id").selectpicker('refresh');
                }
            });
        }

        function limpiarDatoCliente(){
            $('#persona_id').val("");
            $('#tipo_doc_id').selectpicker('val',"");
            $('#nombre').val("");
            $('#paterno').val("");
            $('#materno').val("");
            $('#sexo_id').selectpicker('val',"");
            $('#fecha_nac').val("");
            $('#estado_civil_id').selectpicker('val',"");
            $('#email').val("");
            $('#telefono').val("");
            $('#direccion').val("");

            //Datos cliente
            $('#pais_id').selectpicker('val',"");
            $('#ciudad_id').selectpicker('val',"");
            $('#profesion_id').selectpicker('val',"");
            $('#empresa_id').selectpicker('val',"");
            $('#detalle').val("");

            //Refresh
            $("#tipo_doc_id").selectpicker('refresh');
            $("#sexo_id").selectpicker('refresh');
            $("#estado_civil_id").selectpicker('refresh');
            $("#pais_id").selectpicker('refresh');
            $("#ciudad_id").selectpicker('refresh');
            $("#profesion_id").selectpicker('refresh');
            $("#empresa_id").selectpicker('refresh');

        }

        function setRequiredPersonaNatutal(enabled){
            $("#paterno").prop('required',Boolean(enabled));
            $("#sexo_id").prop('required',Boolean(enabled));
            $("#fecha_nac").prop('required',Boolean(enabled));
            $("#estado_civil_id").prop('required',Boolean(enabled));
        }

  </script>
@endpush

@include('partials/utilesjs')







