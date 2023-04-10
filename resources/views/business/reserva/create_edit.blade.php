
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
               calcularCargo();
          });

          $('#modalViewReserva').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
              $("#cliente_id").focus();
          })

          $(document).on("change", "#fecha_ini", function(){
              calcularCargo()
          });

          $(document).on("change", "#fecha_fin", function(){
             calcularCargo()
          });

          $(document).on("keyup", "#descuento_porcentaje", function(){
             descuentoPorcentaje();
          });

          $(document).on("keyup", "#descuento", function(){
             descuento();
          });

        }); //Fin ready

        function submitFormReserva(event) {
            storeReserva();
            datatable_datos.ajax.reload();//recargar registro datatables.
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function setDateReserva(fecha_ini,fecha_fin){
            $("#fecha_ini").val(formatFecha(fecha_ini));
            $("#fecha_fin").val(formatFecha(fecha_fin));
            calcularCargo()
        }

        function selectHabitacion(habitacion_id){
            $("#habitacion_id").selectpicker('val',habitacion_id);
            $("#habitacion_id").selectpicker('refresh');
            calcularCargo()
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
                    $("#motivo_id").selectpicker('val', result.reserva.motivo_id);
                    $("#motivo_id").selectpicker('refresh');
                    $("#num_adulto").val(result.reserva.num_adulto);
                    $("#num_nino").val(result.reserva.num_nino);
                    $("#procedencia_pais_id").selectpicker('val', result.reserva.procedencia_pais_id);
                    $("#procedencia_pais_id").selectpicker('refresh');
                    $("#fecha_ini").val(formatFecha(result.reserva.fecha_ini));
                    $("#fecha_fin").val(formatFecha(result.reserva.fecha_fin));
                    $("#cantidad").val(result.transaccion.cantidad);
                    $("#precio_unidad").val(result.transaccion.precio_unidad);
                    $("#descuento_porcentaje").val(result.transaccion.descuento_porcentaje);
                    $("#descuento").val(result.transaccion.descuento);
                    $("#monto").val(result.transaccion.monto);
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
                        datatable_datos.ajax.reload();//recargar registro datatables.
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
                url=URL_BASE + "/business/reserva";
                url_delete= url + "/" + $id;

                $.ajax({
                    type: "POST",
                    url: url_delete,
                    data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},
                    dataType: 'json',
                    success: function(result){
                        try {
                            datatable_datos.ajax.reload();//recargar registro datatables.
                        }
                        catch(err) {
                        //En caso de que se cree la reserva desde el TimeLines
                        }
                    },
                    error:function(resultado){

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
                $("#servicio_id").append('<option  value="' + v.id + '" >' + v.servicio + '</option>');
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


        function cantidadReserva(){
            var fecha_ini=$("#fecha_ini").val();
            var fecha_fin=$("#fecha_fin").val();
            var timeStart = new Date(fecha_ini);
            var timeEnd = new Date(fecha_fin);
            var dias=0;
            if (timeEnd >= timeStart)
            {
                var diff = timeEnd.getTime() - timeStart.getTime();
                dias= Math.round(diff / (1000 * 60 * 60 * 24));
                dias=(dias!=0)?dias:1; //Validar cuando sean la misma fecha
            }
            else if (timeEnd != null && timeEnd < timeStart) {
                messageAlert("La fecha de salida debe ser mayor a la fecha de ingreso");
            }
            $("#cantidad").val(dias);
        }

        function precioUnidadReserva(){
            var precio=$('#habitacion_id option:selected').data("precio")
            var precio_unidad=(precio!=null&&precio!=""&&precio>0)?precio:0;
            $("#precio_unidad").val(precio_unidad);
        }


        function descuentoPorcentaje(){
            var cantidad=$("#cantidad").val();
            var precio_unidad=$("#precio_unidad").val();
            var total_cargo=0;
            var porcentaje=$("#descuento_porcentaje").val();
            var descuento=0;

            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
            total_cargo=cantidad*precio_unidad;
            porcentaje=(porcentaje!=null&&porcentaje!=""&&porcentaje>0)?porcentaje:0;
            if(porcentaje>0&&porcentaje<=100){
                descuento=Math.round(parseFloat((total_cargo*porcentaje)/100));
            } else if(porcentaje>100) {
                $("#descuento_porcentaje").val(100);
                descuento=total_cargo;
            } else {
                $("#descuento_porcentaje").val("");
            }
            $("#descuento").val(descuento);
            calcularCargo();

        }

        function descuento(){
            var cantidad=$("#cantidad").val();
            var precio_unidad=$("#precio_unidad").val();
            var total_cargo=0;
            var descuento=$("#descuento").val();
            var porcentaje=0;

            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
            total_cargo=cantidad*precio_unidad;
            descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
            if(descuento>0&&descuento<=total_cargo){
                porcentaje=Math.round(parseFloat((descuento/total_cargo)*100));
            } else if(descuento>total_cargo) {
                $("#descuento").val(total_cargo);
                porcentaje=100;
            } else {
                $("#descuento").val("");
            }
            $("#descuento_porcentaje").val(porcentaje);
            calcularCargo();
        }

        function calcularCargo(){
             cantidadReserva()
             precioUnidadReserva();
             var cargo=0;
             var cantidad=$("#cantidad").val();
             var precio_unidad=$("#precio_unidad").val();
             var descuento=$("#descuento").val();
             //validacion de datos
             cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
             precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
             descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
             cargo= cantidad*precio_unidad-descuento;
             $("#monto").val(cargo.toFixed(2));
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
            $('#cantidad').val("");
            $('#precio_unidad').val("");
            $('#descuento_porcentaje').val("");
            $('#descuento').val("");
            $('#monto').val("");
            $('#detalle').val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







