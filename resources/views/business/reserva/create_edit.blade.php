
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewReserva">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_reserva" class="modal-title">INFORMACION RESERVA</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmReserva" enctype="multipart/form-data" onsubmit="return submitFunction(event)">
                    @csrf

                    <input type="hidden" name="edit" id="edit" value="">
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
              optenerCiudades();
          });

          $(document).on("change", "#habitacion_id", function(){

          });

          $('#modalViewReserva').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
              $("#doc_id").focus();
          })

        }); //Fin ready

        function submitFunction(event) {
            guardarReserva();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function guardarReserva(){

            var formdata = new FormData($("#frmReserva")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/reserva";

            if($("#edit").val()=="modificar"){
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

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewReserva").modal("hide");
                    $("#btnGuardarReserva").removeAttr('disabled');
                    $("#btnGuardarReserva").text("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                    limpiarDatoReserva();
                }//END complete

            }); //End Ajax
       }

       function dataEditReserva($boton){
            limpiarDatoReserva();
            var reserva_id=$boton.id;
            $("#edit").val("modificar");
            $("#title_modal_view_reserva").text("MODIFICAR RESERVA");
            $.ajax({
                type: "GET",
                url: "{{route('editreserva')}}",
                data:{reserva_id:reserva_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $("#reserva_id").val(result.reserva.id);
                    $("#cliente_id").selectpicker('val', result.reserva.cliente_id);
                    $("#cliente_id").selectpicker('refresh');
                    $("#habitacion_id").selectpicker('val', result.reserva.habitacion_id);
                    $("#habitacion_id").selectpicker('refresh');
                    $("#paquete_id").selectpicker('val', result.reserva.paquete_id);
                    $("#paquete_id").selectpicker('refresh');
                    $("#producto_id").selectpicker('val', result.reserva.producto_id);
                    $("#producto_id").selectpicker('refresh');
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

        function optenerCiudades(){
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

        function formatFecha(fecha) { //type="date" solo recibe formato "2023-03-22", caso contrario no carga
            var fecha = new Date(fecha);
            var mes = fecha.getMonth() + 1;
            var dia = fecha.getDate();
            var ano = fecha.getFullYear();
            if (dia < 10)
                dia = '0' + dia; //agrega cero si el menor de 10
            if (mes < 10)
                mes = '0' + mes //agrega cero si el menor de 10
            return ano + "-" + mes + "-" + dia;
        }

        function limpiarDatoReserva(){
            $('#reserva_id').val("");
            $('#cliente_id').selectpicker('val',"");
            $("#cliente_id").selectpicker('refresh');
            $('#habitacion_id').selectpicker('val',"");
            $("#habitacion_id").selectpicker('refresh');
            $('#paquete_id').selectpicker('val', "");
            $("#paquete_id").selectpicker('refresh');
            $('#producto_id').selectpicker('val', "");
            $("#producto_id").selectpicker('refresh');
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







