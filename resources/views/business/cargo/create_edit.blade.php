
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewCargo">
    <div class="modal-dialog modal-xl">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_cargo" class="modal-title">CARGO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmCargo" enctype="multipart/form-data" onsubmit="return submitFormCargo(event)">
                    @csrf
                    <input type="hidden" name="editCargo" id="editCargo" value="">
                    <input type="hidden" name="cargo_id" id="cargo_id" value="">
                    <input type="hidden" name="detalle" id="detalle" value="CARGO">

                    @include('business/cargo/detalle_transaccion')

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarCargo" type="submit">Guardar</button>
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


<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewCamposCargo">
    <div class="modal-dialog modal-xl">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_campos_transaccion" class="modal-title">DATOS CARGO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                @include('business/cargo/campos_cargo')

                <br>

                <div class="row">
                    <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                        <button class="btn btn-success" id="btnAdicionarCargo" type="button">Aceptar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer">

            </div>

        </div>
    </div>
</div> <!--End Modal-->

@push('scripts')
  <script>
        $(document).ready(function() {

            $('#modalViewCargo').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
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
                        calcularSubTotal(fila);
                        registrado=true;
                    }
                });

                $('#hotel_producto_id').selectpicker('val',"");
                $("#hotel_producto_id").selectpicker('refresh');
                //$("#hotel_producto_id").focus();

                if(!registrado) {
                    cargarFilaTransaccion(0,hotel_producto_id,producto,cantidad,precio_unidad,"","","","nuevo")
                }
            });
        });//Fin ready

        function submitFormCargo(event) {
            storeCargo();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function storeCargo(){

            var formdata = new FormData($("#frmCargo")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/cargo";

            if($("#editCargo").val()=="modificar"){
                url= url + "/" + $("#cargo_id").val();
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
                    $("#btnGuardarCargo").attr('disabled','disabled');
                    $("#btnGuardarCargo").html("Procesando");
                },
                success: function(result){

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewCargo").modal("hide");
                    $("#btnGuardarCargo").removeAttr('disabled');
                    $("#btnGuardarCargo").html("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                }//END complete

            }); //End Ajax
       }

       function createCargo(){
            $("#editCargo").val("");
            $("#title_modal_view_cargo").text("NUEVO CARGO");
            limpiarDatoCargo();
            $('#modalViewCargo').modal('show');
       }

       function editCargo($id){
            var transaccion_id=$id;
            $("#editCargo").val("modificar");
            $("#title_modal_view_cargo").text("MODIFICAR CARGO");
            $.ajax({
                type: "GET",
                url: "{{route('editcargo')}}",
                data:{transaccion_id:transaccion_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {
                    limpiarDatoCargo();
                },
                success: function(result){
                    $("#cargo_id").val(result.cargo.id);
                    $("#descuento_porcentaje").val(result.transaccion.descuento_porcentaje);
                    $("#descuento").val(result.transaccion.descuento);

                    $(result.detalle).each(function(i, v){ // indice, valor
                        cargarFilaTransaccion(v.transaccion_id,v.hotel_producto_id,v.producto,v.cantidad,v.precio_unidad,v.descuento_porcentaje,v.descuento,v.monto,"guardado");
                    })

                    $("#modalViewCargo").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            });//End Ajax
        }

        function validateSave(){
            if($('#tbl_detalle>tbody>tr:visible').length > 0){
                $("#btnGuardarCargo").removeAttr("disabled");
            }
            else {
                $("#btnGuardarCargo").attr("disabled","disabled");
            }
        }

        function limpiarDatoCargo(){
            $("#cargo_id").val("");
            $("#cantidad").val("");
            $("#precio_unidad").val("");
            $("#descuento_porcentaje").val("");
            $("#descuento").val("");
            $("#monto").val("");

            //Limpiar detalle
            $("#tbl_detalle tbody tr").find('td').remove();
            $("#cantidad_total").text(0);
            $("#precio_unidad_total").text(0);
            $("#descuento_porcentaje_total").text(0);
            $("#descuento_total").text(0);
            $("#total").text(0);
        }

  </script>
@endpush

@include('partials/utilesjs')







