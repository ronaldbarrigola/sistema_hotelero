
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewTransaccion">
    <div class="modal-dialog modal-xl">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_transaccion" class="modal-title">TRANSACCION</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmTransaccion" enctype="multipart/form-data" onsubmit="return submitFormTransaccion(event)">
                    @csrf
                    <input type="hidden" name="editTransaccion" id="editTransaccion" value="">
                    <input type="hidden" name="transaccion_id" id="transaccion_id" value="">
                    <input type="hidden" name="modulo" id="modulo" value="RESERVA">
                    <input type="hidden" name="foreign_reserva_id" id="foreign_reserva_id" value="">

                    <div id="panel_detalle_transaccion">
                        @include('business/transaccion/detalle_transaccion')
                    </div>

                    <div id="panel_campos_transaccion" style="display:none">
                        @include('business/transaccion/campos_transaccion')
                    </div>

                    <br>
                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarTransaccion" type="submit">Guardar</button>
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

<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" id="modalDeleteTransaccion">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
           <h5 class="modal-title">ELIMINAR</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">X</span>
               </button>
           </div>

           <div class="modal-body">
               <p>¿desea eliminar el registro con id <span id="delete_transaccion_id" style="color:red;"></span> ?</p>
           </div>

           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
               <button type="button" id="btnDeleteTransaccion" class="btn btn-primary" data-dismiss="modal" onclick="deleteTransaccionOK();">Confirmar</button>
           </div>

       </div>
   </div>
</div>

@push('scripts')
  <script>
        $(document).ready(function() {

            $('#modalViewTransaccion').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
                $("#hotel_producto_id").focus();
            })

            $(document).on("change", "#hotel_producto_id", function(){
                var hotel_producto_id=$("#hotel_producto_id").val();
                var producto=$("#hotel_producto_id").find('option:selected').text();
                var precio=$('#hotel_producto_id option:selected').data("precio");
                var precio_unidad=(precio!=null&&precio!=""&&precio>0)?precio:0;


                var registrado=false;
                var cantidad=1;
                $("input[name='vec_hotel_producto_id[]']").each(function(indice, elemento) {
                    if($(elemento).val()==hotel_producto_id){
                        var fila=$(elemento).closest("tr");
                        var vec_cantidad=$(fila).find("input[name='vec_cantidad[]']");
                        var input_cantidad=vec_cantidad[0];
                        cantidad=Number.parseInt(($(input_cantidad).val()!=null)?$(input_cantidad).val():0);
                        $(input_cantidad).val(cantidad +1);
                        transaccionSubTotal(fila);
                        registrado=true;
                    }
                });

                $('#hotel_producto_id').selectpicker('val',"");
                $("#hotel_producto_id").selectpicker('refresh');

                if(!registrado) {
                    cargarFilaTransaccion(0,hotel_producto_id,producto,cantidad,precio_unidad,"","","","nuevo")
                }
            });
        });//Fin ready

        function submitFormTransaccion(event) {
            storeTransaccion();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function storeTransaccion(){

            var formdata = new FormData($("#frmTransaccion")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/transaccion";

            if($("#editTransaccion").val()=="modificar"){
                url= url + "/" + $("#transaccion_id").val();
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
                    $("#btnGuardarTransaccion").attr('disabled','disabled');
                    $("#btnGuardarTransaccion").html("Procesando");
                },
                success: function(result){

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewTransaccion").modal("hide");
                    $("#btnGuardarTransaccion").removeAttr('disabled');
                    $("#btnGuardarTransaccion").html("Guardar");
                    datatable_transaccion.ajax.reload();;//recargar registro datatables.
                }//END complete

            }); //End Ajax
       }

       function createTransaccion(){
            $("#editTransaccion").val("");
            $("#panel_detalle_transaccion").show();
            $("#panel_campos_transaccion").hide();
            $("#title_modal_view_transaccion").text("NUEVO CARGO");
            limpiarDatoTransaccion();
            $.ajax({
                type: "GET",
                url: "{{route('createtransaccion')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataTransaccionAjax(result);
                    $('#modalViewTransaccion').modal('show');
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

       function editTransaccion($id){
            var transaccion_id=$id;
            $("#editTransaccion").val("modificar");
            $("#panel_detalle_transaccion").hide();
            $("#panel_campos_transaccion").show();

            // $('#modalViewTransaccion').removeClass('modal-xl');
            // $('#modalViewTransaccion').addClass('modal-lg');
            $("#title_modal_view_transaccion").text("MODIFICAR CARGO");

            $.ajax({
                type: "GET",
                url: "{{route('edittransaccion')}}",
                data:{transaccion_id:transaccion_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {
                    limpiarDatoTransaccion();
                },
                success: function(result){
                    loadDataTransaccionAjax(result);
                    $("#transaccion_id").val(result.transaccion.id);
                    $("#cantidad").val(result.transaccion.cantidad);
                    $("#precio_unidad").val(result.transaccion.precio_unidad);
                    $("#descuento_porcentaje").val(result.transaccion.descuento_porcentaje);
                    $("#descuento").val(result.transaccion.descuento);
                    $("#monto").val(result.transaccion.monto);
                    $("#modalViewTransaccion").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            });//End Ajax
        }

        function deleteTransaccion($id){
            $("#delete_transaccion_id").text($id);
            $("#modalDeleteTransaccion").modal("show");
        }

        function deleteTransaccionOK(){
            var $id = $('#delete_transaccion_id').text();
            url=URL_BASE + "/business/transaccion";
            url_delete= url + "/" + $id;

            $.ajax({
                type: "POST",
                url: url_delete,
                data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(result){
                    datatable_transaccion.ajax.reload();
                    $("#modalDeleteTransaccion").modal("hide");
                },
                error:function(result){

                }
            });
        }

        function slideTransaccion($id){

            $('#nombre_cliente').text("");
            $('#nro_habitacion').text("");
            $("#tbl_transaccion tbody tr").find('td').remove();
            $('#tbl_transaccion tfoot tr th').html("");

            $('.cabecera_principal').hide();
            $('.cabecera_huesped').hide();
            $('.cabecera_transaccion').show();
            $('.carouselReserva').carousel(1);

            var reserva_id=$id;
            $.ajax({
                type: "GET",
                url: "{{route('obtenerReservaPorId')}}",
                data:{reserva_id:reserva_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    var cliente=result.cliente;
                    var nro_habitacion=result.habitacion.num_habitacion;
                    $('#nombre_cliente').text(cliente.toUpperCase()); //El campo nombre_cliente se encuenta en el modulo transaccion.actionbar
                    $('#nro_habitacion').text(nro_habitacion); //El campo nro_habitacion se encuenta en el modulo transaccion.actionbar
                    $('#foreign_reserva_id').val(reserva_id); //El campo foreign_reserva_id se encuenta en el modulo transaccion.create_edit
                    datatable_transaccion.ajax.reload();
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function loadDataTransaccionAjax(result){
            $("#hotel_producto_id").find('option').remove();
            $("#hotel_producto_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.hotel_productos, function(i, v) {
                $("#hotel_producto_id").append('<option  value="' + v.id + '" data-precio="'+ v.precio +'">' + v.producto +'</option>');
            });
            $("#hotel_producto_id").selectpicker('refresh');
       }

       function limpiarDatoTransaccion(){
            $("#transaccion_id").val("");
            $("#cantidad").val("");
            $("#precio_unidad").val("");
            $("#descuento_porcentaje").val("");
            $("#descuento").val("");
            $("#monto").val("");

            //Limpiar detalle
            $("#tbl_detalle_transaccion tbody tr").find('td').remove();
            $("#cantidad_total").text(0);
            $("#precio_unidad_total").text(0);
            $("#descuento_porcentaje_total").text(0);
            $("#descuento_total").text(0);
            $("#total").text(0);
        }

  </script>
@endpush

@include('partials/utilesjs')







