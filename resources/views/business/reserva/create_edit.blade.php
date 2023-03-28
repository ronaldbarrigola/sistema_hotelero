
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewReserva">
    <!--Los parametros: data-backdrop="static" data-keyboard="false" es para que no se cierre el mormulario modal al hacer click en cualquier parte-->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_reserva" class="modal-title">INFORMACION RESERVA</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmReserva" enctype="multipart/form-data" onsubmit="return submitFormReserva(event)">
                    @csrf

                    <input type="hidden" name="editReserva" id="editReserva" value="">
                    @include('business/reserva/campos_reserva')

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarReserva" type="submit">Guardar</button>
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

          $(document).on("change", "#procedencia_pais_id", function(){
            obtenerProcedenciaCiudades();
          });

          $(document).on("click", "#btnModalCreateCliente", function(){ //El boton btnCreateCliente se encuentra en actionbar
              createCliente();
          });

          $(document).on("change", "#habitacion_id", function(){

          });

          $('#modalViewReserva').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
              $("#cliente_id").focus();
          })

        }); //Fin ready

        function submitFormReserva(event) {
            guardarReserva();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function setDateReserva(fecha_ini,fecha_fin){
              $("#fecha_ini").val(formatFecha(fecha_ini));
              $("#fecha_fin").val(formatFecha(fecha_fin));
        }

        function selectHabitacion(habitacion_id){
            $("#habitacion_id").selectpicker('val',habitacion_id);
            $("#habitacion_id").selectpicker('refresh');
        }

        function guardarReserva(){
            var formdata = new FormData($("#frmReserva")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/reserva";

            if($("#editReserva").val()=="modificar"){
                url= url + "/" + $("#reserva_id").val();
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
                    $("#btnGuardarReserva").attr('disabled','disabled');
                    $("#btnGuardarReserva").text("Procesando");
                },
                success: function(result){
                    $("#modalViewReserva").modal("hide");
                    $("#btnGuardarReserva").removeAttr('disabled');
                    $("#btnGuardarReserva").text("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                    limpiarDatoReserva();
                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function createReserva(){
            $("#editReserva").val("");
            $("#title_modal_view_reserva").text("NUEVA RESERVA");
            limpiarDatoReserva();
            $.ajax({
                type: "GET",
                url: "{{route('createreserva')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){

                    $("#cliente_id").find('option').remove();
                    $("#cliente_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.clientes, function(i, v) {
                        $("#cliente_id").append('<option  value="' + v.id + '" >' + v.cliente + " | " + v.doc_id + '</option>');
                    });
                    $("#cliente_id").selectpicker('refresh');

                    $("#habitacion_id").find('option').remove();
                    $("#habitacion_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.habitaciones, function(i, v) {
                        $("#habitacion_id").append('<option  value="' + v.id + '" >' + v.num_habitacion + " " + v.tipo_habitacion + '</option>');
                    });
                    $("#habitacion_id").selectpicker('refresh');

                    $("#paquete_id").find('option').remove();
                    $("#paquete_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.paquetes, function(i, v) {
                        $("#paquete_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#paquete_id").selectpicker('refresh');

                    $("#procedencia_pais_id").find('option').remove();
                    $("#procedencia_pais_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.paises, function(i, v) {
                        $("#procedencia_pais_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#procedencia_pais_id").selectpicker('refresh');

                    $("#servicio_id").find('option').remove();
                    $("#servicio_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.servicios, function(i, v) {
                        $("#servicio_id").append('<option  value="' + v.id + '" >' + v.servicio + '</option>');
                    });
                    $("#servicio_id").selectpicker('refresh');

                    $("#motivo_id").find('option').remove();
                    $("#motivo_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.motivos, function(i, v) {
                        $("#motivo_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#motivo_id").selectpicker('refresh');

                    $("#modalViewReserva").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax

        }

       function editReserva($boton){
            limpiarDatoReserva();
            var reserva_id=$boton.id;
            $("#editReserva").val("modificar");
            $("#title_modal_view_reserva").text("MODIFICAR RESERVA");
            $.ajax({
                type: "GET",
                url: "{{route('editreserva')}}",
                data:{reserva_id:reserva_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){

                    $("#cliente_id").find('option').remove();
                    $("#cliente_id").append('<option  value=""></option>');
                    $.each(result.clientes, function(i, v) {
                        $("#cliente_id").append('<option  value="' + v.id + '" >' + v.cliente + " | " + v.doc_id + '</option>');
                    });
                    $("#cliente_id").selectpicker('refresh');

                    $("#habitacion_id").find('option').remove();
                    $("#habitacion_id").append('<option  value=""></option>');
                    $.each(result.habitaciones, function(i, v) {
                        $("#habitacion_id").append('<option  value="' + v.id + '" >' + v.num_habitacion + " " + v.tipo_habitacion + '</option>');
                    });
                    $("#habitacion_id").selectpicker('refresh');

                    $("#paquete_id").find('option').remove();
                    $("#paquete_id").append('<option  value=""></option>');
                    $.each(result.paquetes, function(i, v) {
                        $("#paquete_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#paquete_id").selectpicker('refresh');

                    $("#procedencia_pais_id").find('option').remove();
                    $("#procedencia_pais_id").append('<option  value=""></option>');
                    $.each(result.paises, function(i, v) {
                        $("#procedencia_pais_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#procedencia_pais_id").selectpicker('refresh');

                    $("#servicio_id").find('option').remove();
                    $("#servicio_id").append('<option  value=""></option>');
                    $.each(result.servicios, function(i, v) {
                        $("#servicio_id").append('<option  value="' + v.id + '" >' + v.servicio + '</option>');
                    });
                    $("#servicio_id").selectpicker('refresh');

                    $("#motivo_id").find('option').remove();
                    $("#motivo_id").append('<option  value=""></option>');
                    $.each(result.motivos, function(i, v) {
                        $("#motivo_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#motivo_id").selectpicker('refresh');


                    $("#reserva_id").val(result.reserva.id);
                    $("#cliente_id").selectpicker('val', result.reserva.cliente_id);
                    $("#cliente_id").selectpicker('refresh');
                    $("#habitacion_id").selectpicker('val', result.reserva.habitacion_id);
                    $("#habitacion_id").selectpicker('refresh');
                    $("#paquete_id").selectpicker('val', result.reserva.paquete_id);
                    $("#paquete_id").selectpicker('refresh');
                    $("#servicio_id").selectpicker('val', result.reserva.servicio_id);
                    $("#servicio_id").selectpicker('refresh');
                    $("#motivo_id").selectpicker('val', result.reserva.motivo_id);
                    $("#motivo_id").selectpicker('refresh');
                    $("#num_adulto").val(result.reserva.num_adulto);
                    $("#num_nino").val(result.reserva.num_nino);
                    $("#procedencia_pais_id").selectpicker('val', result.reserva.procedencia_pais_id);
                    $("#procedencia_pais_id").selectpicker('refresh');
                    $("#fecha_ini").val(formatFecha(result.reserva.fecha_ini));
                    $("#fecha_fin").val(formatFecha(result.reserva.fecha_fin));
                    $("#detalle").val(result.reserva.detalle);

                    $("#procedencia_ciudad_id").find('option').remove();
                    $.each(result.ciudades , function(i, v) {
                        $("#procedencia_ciudad_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#procedencia_ciudad_id").selectpicker('refresh');
                    $("#procedencia_ciudad_id").selectpicker('val', result.reserva.procedencia_ciudad_id);
                    $("#procedencia_ciudad_id").selectpicker('refresh');

                    $("#modalViewReserva").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax

        }

        function obtenerProcedenciaCiudades(){
            var pais_id= $("#procedencia_pais_id").val();
            $.ajax({
                type: "get",
                url: "{{route('listaciudades')}}",
                data:{pais_id:pais_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(response){
                    $("#procedencia_ciudad_id").find('option').remove();
                    $("#procedencia_ciudad_id").append('<option value="">--Seleccione--</option>');
                    $.each( response , function(i, v) {
                        $("#procedencia_ciudad_id").append('<option value="' + v.id + '">' + v.descripcion + '</option>');
                    });
                    $("#procedencia_ciudad_id").selectpicker('refresh');
                }
            });
        }

        function limpiarDatoReserva(){
            $('#reserva_id').val("");
            $('#cliente_id').selectpicker('val',"");
            $("#cliente_id").selectpicker('refresh');
            $('#habitacion_id').selectpicker('val',"");
            $("#habitacion_id").selectpicker('refresh');
            $('#paquete_id').selectpicker('val', "");
            $("#paquete_id").selectpicker('refresh');
            $('#servicio_id').selectpicker('val', "");
            $("#servicio_id").selectpicker('refresh');
            $('#motivo_id').selectpicker('val', "");
            $("#motivo_id").selectpicker('refresh');
            $('#num_adulto').val("");
            $('#num_nino').val("");
            $('#procedencia_pais_id').selectpicker('val',"");
            $("#procedencia_pais_id").selectpicker('refresh');
            $('#procedencia_ciudad_id').selectpicker('val',"");
            $("#procedencia_ciudad_id").selectpicker('refresh');
            $('#fecha_ini').val("");
            $('#fecha_fin').val("");
            $('#detalle').val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







