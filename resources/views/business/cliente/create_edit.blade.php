
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewCliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_cliente" class="modal-title">INFORMACION CLIENTE</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmCliente" enctype="multipart/form-data" onsubmit="return submitFunction(event)">
                    @csrf

                    <input type="hidden" name="edit" id="edit" value="">

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
              optenerCiudades();
          });

          $('#modalViewCliente').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
              $("#doc_id").focus();
          })

        }); //Fin ready

        function submitFunction(event) {
            guardarCliente();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function guardarCliente(){

            var formdata = new FormData($("#frmCliente")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/cliente";

            if($("#edit").val()=="modificar"){
                url= url + "/" + $("#cliente_id").val();
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
                    $("#btnGuardarCliente").text("Procesando");
                },
                success: function(result){

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewCliente").modal("hide");
                    $("#btnGuardarCliente").removeAttr('disabled');
                    $("#btnGuardarCliente").text("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                    $("#doc_id").val("");
                    limpiarDatoCliente();
                }//END complete

            }); //End Ajax
       }

       function dataEditCliente($boton){
            limpiarDatoCliente();
            var cliente_id=$boton.id;
            $("#edit").val("modificar");
            $("#title_modal_view_cliente").text("MODIFICAR CLIENTE");
            $.ajax({
                type: "GET",
                url: "{{route('editcliente')}}",
                data:{cliente_id:cliente_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){

                },//End success
                complete:function(result, textStatus ){
                    var data=result.responseJSON;
                        //Datos persona
                    $('#doc_id').val(data.persona.doc_id);
                    $('#tipo_doc_id').selectpicker('val', data.persona.tipo_doc_id);
                    $("#tipo_doc_id").selectpicker('refresh');
                    $('#nombre').val(data.persona.nombre);
                    $('#paterno').val(data.persona.paterno);
                    $('#materno').val(data.persona.materno);
                    $('#sexo_id').selectpicker('val', data.persona.sexo_id);
                    $("#sexo_id").selectpicker('refresh');
                    $('#fecha_nac').val( formatFecha(data.persona.fecha_nac));
                    $('#estado_civil_id').selectpicker('val', data.persona.estado_civil_id);
                    $("#estado_civil_id").selectpicker('refresh');
                    $('#email').val(data.persona.email);
                    $('#telefono').val(data.persona.telefono);
                    $('#direccion').val(data.persona.direccion);

                    //Datos cliente
                    $('#cliente_id').val(data.cliente.id);
                    $('#pais_id').selectpicker('val', data.cliente.pais_id);
                    $("#pais_id").selectpicker('refresh');
                    $('#profesion_id').selectpicker('val', data.cliente.profesion_id);
                    $("#profesion_id").selectpicker('refresh');
                    $('#empresa_id').selectpicker('val', data.cliente.empresa_id);
                    $("#empresa_id").selectpicker('refresh');
                    $('#detalle').val(data.cliente.detalle);

                    $("#ciudad_id").find('option').remove();
                    $.each( data.ciudades , function(i, v) {
                        $("#ciudad_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#ciudad_id").selectpicker('refresh');
                    $('#ciudad_id').selectpicker('val', data.cliente.ciudad_id);
                    $("#ciudad_id").selectpicker('refresh');

                    $("#modalViewCliente").modal("show");
                }
            }); //End Ajax

        }

        function optenerCiudades(){
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
            $('#cliente_id').val("");
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

        function formatFecha(fecha) {
            var fecha = new Date(fecha); //Fecha actual
            var mes = fecha.getMonth() + 1; //obteniendo mes
            var dia = fecha.getDate(); //obteniendo dia
            var ano = fecha.getFullYear(); //obteniendo año
            if (dia < 10)
                dia = '0' + dia; //agrega cero si el menor de 10
            if (mes < 10)
                mes = '0' + mes //agrega cero si el menor de 10
            return dia + "/" + mes + "/" + ano;
        }

  </script>
@endpush

@include('partials/utilesjs')







