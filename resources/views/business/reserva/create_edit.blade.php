
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
                    <input type="hidden" name="modulo" id="modulo" value="RESERVA">
                    @include('business/reserva/campos_reserva')

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarReserva" type="submit">Guardar</button>
                            <button type="button" id="btnGuardarReservaCancel" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer">

            </div>

        </div>
    </div>
</div> <!--End Modal-->


<!--Begin Modal Eliminar-->
<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" id="modalDeleteReserva">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">ELIMINAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                <p>¿desea eliminar el registro con id <span id="delete_reserva_id" style="color:red;"></span> ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnDeleteReserva" class="btn btn-primary" data-dismiss="modal" onclick="deleteReservaOK();">Confirmar</button>
            </div>

        </div>
    </div>
 </div>
 <!--End Modal Eliminar-->

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
              var precio=$('#habitacion_id option:selected').data("precio");
              var precio_unidad_ref=(precio!=null&&precio!=""&&precio>0)?precio:0;
              $("#precio_unidad_ref").val(precio_unidad_ref);
              servicioReserva();
              reservaCalcularCargo();
          });

          $('#modalViewReserva').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
              $("#cliente_id").focus();
          })

          $(document).on("change", "#fecha_ini", function(){
             reservaCalcularCargo();
          });

          $(document).on("change", "#fecha_fin", function(){
             reservaCalcularCargo();
          });

          $(document).on("change", "#servicio_id", function(){
             servicioReserva();
             reservaCalcularCargo();
          });

        }); //Fin ready

        function submitFormReserva(event) {
            storeReserva();
            //datatable_datos.ajax.reload();//recargar registro datatables.
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function setDateReserva(fecha_ini,fecha_fin){
            $("#fecha_ini").val(formatFecha(fecha_ini));
            $("#fecha_fin").val(formatFecha(fecha_fin));
            reservaCalcularCargo()
        }

        function selectHabitacion(habitacion_id){
            $("#habitacion_id").selectpicker('val',habitacion_id);
            $("#habitacion_id").selectpicker('refresh');

            //Cargar precio de habitacion
            var precio=$('#habitacion_id option:selected').data("precio");
            var precio_unidad_ref=(precio!=null&&precio!=""&&precio>0)?precio:0;
            $("#precio_unidad_ref").val(precio_unidad_ref);
            servicioReserva();
            reservaCalcularCargo();
        }

       function createReserva(){
            $("#editReserva").val("");
            $("#title_modal_view_reserva").text("NUEVA RESERVA");
            limpiarDatoReserva();
            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('createreserva')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataReservaAjax(result)
                    $("#modalViewReserva").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax

        }

        function editReserva($id){
            limpiarDatoReserva();
            var reserva_id=$id;
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

                    loadDataReservaAjax(result)

                    $("#reserva_id").val(result.reserva.id);
                    $("#cliente_id").selectpicker('val', result.reserva.cliente_id);
                    $("#cliente_id").selectpicker('refresh');
                    $("#habitacion_id").selectpicker('val', result.reserva.habitacion_id);
                    $("#habitacion_id").selectpicker('refresh');
                    $("#paquete_id").selectpicker('val', result.reserva.paquete_id);
                    $("#paquete_id").selectpicker('refresh');
                    $("#servicio_id").selectpicker('val', result.reserva.servicio_id);
                    $("#servicio_id").selectpicker('refresh');
                    if(result.reserva.servicio_id!=null&&result.reserva.servicio_id==2){ //1:HOSPEDAJE 2:DAY USE
                       $("#precio_unidad").removeAttr('readonly');
                    } else {
                       $("#precio_unidad").attr('readonly','readonly');
                    }
                    $("#motivo_id").selectpicker('val', result.reserva.motivo_id);
                    $("#motivo_id").selectpicker('refresh');
                    $("#num_adulto").val(result.reserva.num_adulto);
                    $("#num_nino").val(result.reserva.num_nino);
                    $("#procedencia_pais_id").selectpicker('val', result.reserva.procedencia_pais_id);
                    $("#procedencia_pais_id").selectpicker('refresh');
                    $("#fecha_ini").val(formatFecha(result.reserva.fecha_ini));
                    $("#fecha_fin").val(formatFecha(result.reserva.fecha_fin));
                    $("#hora_ini").val(result.reserva.hora_ini);
                    $("#hora_fin").val(result.reserva.hora_fin);
                    $("#reserva_cantidad").val(result.transaccion.cantidad);
                    $("#reserva_precio_unidad").val(result.transaccion.precio_unidad);
                    //BEGIN:Precio unidad habitacion referencial
                    var precio=$('#habitacion_id option:selected').data("precio");
                    var precio_unidad_ref=(precio!=null&&precio!=""&&precio>0)?precio:0;
                    $("#reserva_precio_unidad_ref").val(precio_unidad_ref);
                    //END:Precio unidad habitacion referencial
                    $("#reserva_descuento_porcentaje").val(result.transaccion.descuento_porcentaje);
                    $("#reserva_descuento").val(result.transaccion.descuento);
                    $("#reserva_monto").val(result.transaccion.monto);
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

        function storeReserva(){

            //Validacion de fecha HOSPEDAJE Y DAY USE
            var servicio_id=$("#servicio_id").val();
            var fecha_ini=$("#fecha_ini").val();
            var fecha_fin=$("#fecha_fin").val();
            var timeStart = new Date(fecha_ini);
            var timeEnd = new Date(fecha_fin);

            if (timeEnd != null && timeEnd < timeStart) {
                messageAlert("La fecha ingreso debe ser mayor a la fecha de salida");
                return 0;
            }

            if(servicio_id==2){ //1: HOSPEDAJE 2:DAY USE
                if (timeEnd != null && timeEnd > timeStart){
                    messageAlert("SERVICIO DAY USE : \n La fecha de ingreso debe ser igual a la fecha de salida");
                    return 0;
                }
            } else {
                if (timeEnd <= timeStart){
                    messageAlert("SERVICIO HOSPEDAJE : \n La fecha de ingreso debe ser mayor a la fecha de salida");
                    return 0;
                }
            }

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
                    $("#btnGuardarReserva").html("Procesando");  //En un input tipo submit el texto se cambia en val() antes $("#btnGuardarReserva").text("Procesando");
                },
                success: function(result){
                    limpiarDatoReserva();

                    try {
                        datatable_reserva.ajax.reload();//recargar registro datatables.
                    }
                    catch(err) {
                      //En caso de que se cree la reserva desde el TimeLines
                    }
                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){
                    $("#btnGuardarReserva").removeAttr('disabled');
                    $("#btnGuardarReserva").html("Guardar");
                    $("#modalViewReserva").modal("hide");
                }//END complete

            }); //End Ajax
       }

        function deleteReserva($id){
            $("#delete_reserva_id").text($id);
            $("#modalDeleteReserva").modal("show");
        }

        function deleteReservaOK(){
            var $id = $('#delete_reserva_id').text();
            url=URL_BASE + "/business/reserva";
            url_delete= url + "/" + $id;

            $.ajax({
                type: "POST",
                url: url_delete,
                data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(result){
                    datatable_reserva.ajax.reload();
                    $("#modalDeleteReserva").modal("hide");
                },
                error:function(result){

                }
            });
        }

        function loadDataReservaAjax(result){
            $("#cliente_id").find('option').remove();
            $("#cliente_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.clientes, function(i, v) {
                $("#cliente_id").append('<option  value="' + v.id + '" >' + v.cliente + " | " + v.doc_id + '</option>');
            });
            $("#cliente_id").selectpicker('refresh');

            $("#habitacion_id").find('option').remove();
            $("#habitacion_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.habitaciones, function(i, v) {
                $("#habitacion_id").append('<option data-precio="'+ v.precio +'"  value="' + v.id + '" >' + v.num_habitacion + " " + v.tipo_habitacion + '</option>');
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
                $("#servicio_id").append('<option   value="' + v.id + '"  data-hora_ini="' + v.hora_ini + '" data-hora_fin="' + v.hora_fin + '">' + v.servicio + '</option>');
            });
            $("#servicio_id").selectpicker('refresh');

            $("#motivo_id").find('option').remove();
            $("#motivo_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.motivos, function(i, v) {
                $("#motivo_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
            });
            $("#motivo_id").selectpicker('refresh');

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

        function servicioReserva(){
            var servicio_id=$("#servicio_id").val();
            var fecha_ini=$("#fecha_ini").val();
            var fecha_fin=$("#fecha_fin").val();
            var hora_ini=$('#servicio_id option:selected').data("hora_ini");
            var hora_fin=$('#servicio_id option:selected').data("hora_fin");
            var edit_reserva=$("#editReserva").val();
            var fecha_actual=fechaActual();
            if(servicio_id==2){ //1: HOSPEDAJE 2:DAY USE
                if(edit_reserva!="modificar"){
                    $("#fecha_ini").val(fecha_actual);
                    $("#fecha_fin").val(fecha_actual);
                    $("#hora_ini").val(hora_ini);
                    $("#hora_fin").val(hora_fin);
                }

                var precio=$('#habitacion_id option:selected').data("precio")
                var precio_unidad=(precio!=null&&precio!=""&&precio>0)?precio:0;
                var precio_day_use=precio_unidad-precio_unidad*0.5; //Descontando el 50% por precio day use
                $("#reserva_precio_unidad").val(precio_day_use.toFixed(2));

            } else {
                if(edit_reserva!="modificar"){
                    if(fecha_ini==null||fecha_ini==""){
                        $("#fecha_ini").val(fecha_actual);
                        $("#fecha_fin").val(addDaysToDate(fecha_actual,2));
                    }

                    $("#hora_ini").val(hora_ini);
                    $("#hora_fin").val(hora_fin);
                }
            }
        }

        function addDaysToDate(fecha,dias){
            var fecha = new Date(fecha);
            var mes = fecha.getMonth() + 1;
            var dia = fecha.getDate() + dias;
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
            $('#hora_ini').val("");
            $('#hora_fin').val("");
            $('#reserva_cantidad').val("");
            $('#reserva_precio_unidad').val("");
            $('#reserva_precio_unidad_ref').val("");
            $('#reserva_descuento_porcentaje').val("");
            $('#reserva_descuento').val("");
            $('#reserva_monto').val("");
            $('#detalle').val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







