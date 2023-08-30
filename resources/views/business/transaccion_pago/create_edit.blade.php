
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewTransaccionPago">
    <div class="modal-dialog modal-xl">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_transaccion_pago" class="modal-title">TRANSACCION PAGO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmTransaccionPago" enctype="multipart/form-data" onsubmit="return submitFormTransaccionPago(event)">
                    @csrf
                    <input type="hidden" name="editTransaccionPago" id="editTransaccionPago" value="">
                    <input type="hidden" name="foreign_transaccion_id" id="foreign_transaccion_id" value="">
                    <input type="hidden" name="transaccion_pago_id" id="transaccion_pago_id" value="">
                    <input type="hidden" name="tipo_pago" id="tipo_pago" value="">

                    <div class="panel_pago">
                        @include('business/transaccion_pago/campos_pago')
                        @include('business/transaccion_pago/detalle_pago')
                    </div>

                    <div class="panel_anticipo" style="display:none">
                        @include('business/transaccion_pago/campos_anticipo')
                    </div>

                    @include('business/formapago/detalle_forma_pago')
                    <br>
                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarTransaccionPago" type="submit">Guardar</button>
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

            $('#modalViewTransaccionPago').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
                if ($('.panel_anticipo').is(':visible')) {
                    $("#anticipo_monto").focus();
                } else {
                    $("#pago_cliente_id").focus();
                }
            })

            $(document).on("change", "#pago_cliente_id", function(){
                var nit=$('#pago_cliente_id option:selected').data("nit");
                var nombre=$('#pago_cliente_id option:selected').data("nombre");
                var celular=$('#pago_cliente_id option:selected').data("celular");
                var email=$('#pago_cliente_id option:selected').data("email");
                $("#pago_nit").val(nit);
                $("#pago_nombre").val(nombre);
                $("#pago_celular").val(celular);
                $("#pago_email").val(email);
            });

            $('#pago_nit').on('blur',function(e){
               var nit=$("#pago_nit").val()
               if(nit!=""&&nit!=null) {
                  datoFactura(nit)
               } else {
                   $("#pago_nombre").val("");
                   $("#pago_celular").val("");
                   $("#pago_email").val("");
               }
            });

        });//Fin ready

        function submitFormTransaccionPago(event) {
           forma_pago_id=$('#forma_pago_id').val();
           if(forma_pago_id=="PM"){
               saldo=parseFloat($("#fp_saldo").val());
               saldo_abs=Math.abs(saldo);//valor absoluto
               total=$("#fp_monto_base").val();
               if(saldo_abs > 0){
                  messageAlert(`Debe pagar el monto de : ${total} Bs.`);
                  event.preventDefault(); //cancela el evento
                  return false; //Cancela el envio submit para procesar por ajax
               }
           }

           storeTransaccionPago();
           event.preventDefault(); //cancela el evento
           return false; //Cancela el envio submit para procesar por ajax
        }

        function storeTransaccionPago(){
            var formdata = new FormData($("#frmTransaccionPago")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/transaccion_pago";

            if($("#editTransaccionPago").val()=="modificar"){
                url= url + "/" + $("#transaccion_pago_id").val();
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
                    $("#btnGuardarTransaccionPago").attr('disabled','disabled');
                    $("#btnGuardarTransaccionPago").html("Procesando");
                },
                success: function(result){
                    $("#modalViewTransaccionPago").modal("hide");
                    $("#btnGuardarTransaccionPago").removeAttr('disabled');
                    $("#btnGuardarTransaccionPago").html("Guardar");
                    datatable_transaccion.ajax.reload();
                    updateItemForId(result.transaccion.reserva_id);
                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function createTransaccionPago(){
            $("#editTransaccionPago").val("");
            $("#tipo_pago").val("PAGO");
            $("#title_modal_view_transaccion_pago").text("PAGO");
            $('.panel_anticipo').hide();
            $('.panel_pago').show();
            var reserva_id=$("#foreign_reserva_id").val();
            requiredPago(true);
            requiredAnticipo(false);
            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('createtransaccionpago')}}",
                data:{reserva_id:reserva_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataTransaccionPagoAjax(result)
                    $.each(result.formaPagos, function(i, v) {
                        if(v.id!="PM"){//No debe cargar la opcion pago multiple en la tabla tbl_forma_pago
                           cargarFilaFormaPago(0,v.id,v.descripcion,"","") //Se encuentra en el modulo formapago
                        }
                    });
                    $('#modalViewTransaccionPago').modal('show');
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function createTransaccionAnticipo($this){
            var transaccion_id=$this.id;
            $("#editTransaccionPago").val("");
            $("#foreign_transaccion_id").val(transaccion_id);

            requiredPago(false);
            requiredAnticipo(true);
            limpiarDatoTransaccionPago();//Se ecuentra en el modulo transaccion_pago
            limpiarFormaPago();//Se ecuentra en el modulo formapago

            var fila=$($this).closest("tr");
            var vec_monto=$(fila).find("input[name='tr_monto[]']");
            var input_monto=vec_monto[0];
            var monto=$(input_monto).val();
            $("#anticipo_cargo").val(monto);
            $("#anticipo_saldo").val(monto);

            $("#tipo_pago").val("ANTICIPO");
            $("#title_modal_view_transaccion_pago").text("ANTICIPO");
            $('.panel_anticipo').show();
            $('.panel_pago').hide();

            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('createtransaccionpago')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataTransaccionPagoAjax(result)
                    $.each(result.formaPagos, function(i, v) {
                        if(v.id!="PM"){//No debe cargar la opcion pago multiple en la tabla tbl_forma_pago
                           cargarFilaFormaPago(0,v.id,v.descripcion,"","") //Se encuentra en el modulo formapago
                        }
                    });
                    $('#modalViewTransaccionPago').modal('show');
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

       function editTransaccionPago($id){
            var transaccion_pago_id=$id;
            $("#editTransaccionPago").val("modificar");
            $("#title_modal_view_transaccion_pago").text("MODIFICAR PAGO");
            $.ajax({
                type: "GET",
                url: "{{route('edittransaccion')}}",
                data:{transaccion_pago_id:transaccion_pago_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {
                    loadDataTransaccionPagoAjax(result)
                    limpiarDatoTransaccionPago();
                },
                success: function(result){
                    $("#transaccion_pago_id").val(result.transaccion_pago.id);
                    $("#modalViewTransaccionPago").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            });//End Ajax
        }

        function datoFactura(nit){
            $.ajax({
                type: "GET",
                url: "{{route('dato_factura')}}",
                data:{nit:nit,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(result){
                    if(result.response){
                        $("#pago_nit").val(result.datofactura.nit);
                        $("#pago_nombre").val(result.datofactura.nombre);
                        $("#pago_celular").val(result.datofactura.celular);
                        $("#pago_email").val(result.datofactura.email);
                    } else {
                        $("#pago_nombre").val("");
                        $("#pago_celular").val("");
                        $("#pago_email").val("");
                    }
                }
            });//End Ajax
        }

        function loadDataTransaccionPagoAjax(result){
            $("#forma_pago_id").find('option').remove();
            $("#forma_pago_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.formaPagos, function(i, v) {
                $("#forma_pago_id").append('<option  value="'+ v.id +'" >'+v.descripcion+'</option>');
            });
            $("#forma_pago_id").selectpicker('refresh');

            $("#pago_cliente_id").find('option').remove();
            $("#pago_cliente_id").append('<option  value="" selected>--Seleccione--</option>');
            $.each(result.huespedes, function(i, v) {
                $("#pago_cliente_id").append('<option  value="'+ v.cliente_id +'"  data-nit="'+ v.nit +'" data-nombre="'+ v.nombre +'" data-celular="'+ v.celular +'" data-email="'+ v.email +'">'+v.cliente+'</option>');
            });
            $("#pago_cliente_id").selectpicker('refresh');
        }

        function limpiarDatoTransaccionPago(){
            $("#pago_nombre").val("");
            $("#pago_nit").val("");
            $("#pago_celular").val("");
            $("#pago_email").val("");
            $("#pago_detalle").val("");
            $("#tbl_detalle_transaccion_pago tbody tr").find('td').remove();
            $("#total_pago").text(0);

            //Datos anticipo
            $("#anticipo_monto").val("");
            $("#anticipo_detalle").val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







