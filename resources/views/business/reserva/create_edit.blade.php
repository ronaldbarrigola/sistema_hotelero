
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewReserva">
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

@include('business/comprobante/reserva')

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
              $("#reserva_precio_unidad_ref").val(precio_unidad_ref);
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
            event.preventDefault();//cancela el evento
            return false;//Cancela el envio submit para procesar por ajax
        }

        function setDateReserva(fecha_ini,fecha_fin){
            $("#fecha_ini").val(formatFecha(fecha_ini));
            $("#fecha_fin").val(formatFecha(fecha_fin));
            reservaCalcularCargo()
        }

        function setHabitacion(habitacion_id){
            $("#habitacion_id").selectpicker('val',habitacion_id);
            $("#habitacion_id").selectpicker('refresh');

            //$("#habitacion_id").prop( "disabled",true);

            //Cargar precio de habitacion
            var precio=$('#habitacion_id option:selected').data("precio");
            var precio_unidad_ref=(precio!=null&&precio!=""&&precio>0)?precio:0;
            $("#reserva_precio_unidad_ref").val(precio_unidad_ref);
            servicioReserva();
            reservaCalcularCargo();
        }

       function createReserva(){
            $("#editReserva").val("");
            $(".panel_huesped").show();
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
            $(".panel_huesped").hide();
            $("#title_modal_view_reserva").text("MODIFICAR RESERVA");
            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
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
                    //Cargar precio de habitacion
                    var precio=$('#habitacion_id option:selected').data("precio");
                    var precio_unidad_ref=(precio!=null&&precio!=""&&precio>0)?precio:0;
                    $("#reserva_precio_unidad_ref").val(precio_unidad_ref);

                    $("#paquete_id").selectpicker('val', result.reserva.paquete_id);
                    $("#paquete_id").selectpicker('refresh');
                    $("#servicio_id").selectpicker('val', result.reserva.servicio_id);
                    $("#servicio_id").selectpicker('refresh');
                    $("#canal_reserva_id").selectpicker('val', result.reserva.canal_reserva_id);
                    $("#canal_reserva_id").selectpicker('refresh');
                    if(result.reserva.servicio_id!=null&&result.reserva.servicio_id==2){ //1:HOSPEDAJE 2:DAY USE
                       $("#precio_unidad").removeAttr('readonly');
                    } else {
                       $("#precio_unidad").attr('readonly','readonly');
                    }
                    $("#motivo_id").selectpicker('val', result.reserva.motivo_id);
                    $("#motivo_id").selectpicker('refresh');
                    $("#procedencia_pais_id").selectpicker('val', result.reserva.procedencia_pais_id);
                    $("#procedencia_pais_id").selectpicker('refresh');
                    $("#fecha_ini").val(formatFecha(result.reserva.fecha_ini));
                    $("#fecha_fin").val(formatFecha(result.reserva.fecha_fin));
                    $("#hora_ini").val(result.reserva.hora_ini);
                    $("#hora_fin").val(result.reserva.hora_fin);

                    $("#reserva_cantidad").val(result.transaccion.cantidad);
                    $("#reserva_precio_unidad").val(result.transaccion.precio_unidad);
                    $("#reserva_descuento_porcentaje").val(result.transaccion.descuento_porcentaje);
                    $("#reserva_descuento").val(result.transaccion.descuento);
                    $("#reserva_monto").val(result.transaccion.monto);
                    $("#reserva_anticipo").val(result.transaccion_pago.monto);//Monto que corresponde a anticipo

                    //saldo
                    $monto=(result.transaccion.monto!=null)?result.transaccion.monto:0;
                    $anticipo=(result.transaccion_pago.monto!=null)?result.transaccion_pago.monto:0;
                    $saldo=$monto-$anticipo;
                    $("#reserva_saldo").val($saldo);

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
                    var reserva_id=result.reserva.id;
                    try {
                        datatable_reserva.ajax.reload();//recargar registro datatables.
                    }
                    catch(err){
                        //En caso de que se cree la reserva desde el TimeLines
                    }

                    //Actualizar datos de Item en TimeLines
                    try {
                        updateItemForId(reserva_id);
                    } catch(err){}

                    //View Comprobante de reserva
                    comprobanteReserva(reserva_id);
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
            boot4.confirm({
                msg:"Esta seguro de eliminar la reserva?",
                title:"Confirmación",
                callback:function(result){
                    if(result){
                        executeDeleteReserva($id)
                    }//fin if
                }
            });
        }

        function executeDeleteReserva($id){ //La eliminacion es tambien desde calendario
            url=URL_BASE + "/business/reserva";
            url_delete= url + "/" + $id;
            $.ajax({
                type: "POST",
                url: url_delete,
                data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(result){
                    if(result.response){
                        try {
                            datatable_reserva.ajax.reload();//recargar registro datatables.
                        }
                        catch(err) {
                            //En caso de que se cree la reserva desde el TimeLines
                        }
                    } else {
                        messageAlert(result.message);
                    }

                },
                error:function(result){

                }
            });
        }

        function loadDataReservaAjax(result){
            $("#cliente_id").find('option').remove();
            $("#cliente_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.clientes, function(i, v) {
                $("#cliente_id").append('<option  value="' + v.id + '" >' + v.cliente + " " + v.doc_id + '</option>');
            });
            $("#cliente_id").selectpicker('refresh');

            $("#habitacion_id").find('option').remove();
            $("#habitacion_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.habitaciones, function(i, v) {
                $("#habitacion_id").append('<option data-precio="'+ v.precio +'"  value="' + v.id + '" >' + v.num_habitacion + " " + v.tipo_habitacion + '</option>');
            });
            $("#habitacion_id").selectpicker('refresh');

            $("#canal_reserva_id").find('option').remove();
            $("#canal_reserva_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.canal_reserva, function(i, v) {
                $("#canal_reserva_id").append('<option  value="' + v.id + '" >' + v.nombre + '</option>');
            });
            $("#canal_reserva_id").selectpicker('refresh');

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
                }

                setHoraDayUse();

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
                }

                $("#hora_ini").val(hora_ini);
                $("#hora_fin").val(hora_fin);
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

        function setHoraDayUse() {
            var fecha = new Date();// Obtiene la fecha y hora actual
            var hora = fecha.getHours();
            var minutos = fecha.getMinutes();
            var segundos = fecha.getSeconds();
            var hora_ini=hora;
            var hora_fin=hora+1;

            // Agrega ceros a la izquierda si es necesario
            if (hora_ini < 10) hora_ini = "0" + hora_ini;
            if (hora_fin < 10) hora_fin = "0" + hora_fin;
            if (minutos < 10) minutos = "0" + minutos;

            $('#hora_ini').val(hora_ini + ":" + minutos);
            $('#hora_fin').val(hora_fin + ":" + minutos);
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
            $('#reserva_anticipo').val("");
            $('#reserva_saldo').val("");
            $('#detalle').val("");
        }

        function slideReserva(){
            $('.cabecera_transaccion').hide()
            $('.cabecera_huesped').hide()
            $('.cabecera_principal').show()
            $('.carouselReserva').carousel(0);
            try {
                timeline.redraw();
            } catch(err) {} //Esto soluciona el problema de refrescar el item en timeline ante cualquier cambio o actualizacion
        }

  </script>
@endpush

@include('partials/utilesjs')







