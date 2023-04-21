
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewHotelProducto">
    <div class="modal-dialog modal-lg">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_hotel_producto" class="modal-title">INFORMACION PRODUCTO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmHotelProducto" enctype="multipart/form-data" onsubmit="return submitFunctionHotelProducto(event)">
                    @csrf

                    <input type="hidden" name="editHotelProducto" id="editHotelProducto" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS PRODUCTO</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/hotel_producto/campos_hotel_producto')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarHotelProducto" type="submit">Guardar</button>
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

<!--Begin Modal Eliminar-->
<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" id="modalDeleteHotelProducto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">ELIMINAR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                <p>¿desea eliminar el registro con id <span id="delete_hotel_producto_id" style="color:red;"></span> ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnDeleteHotelProducto" class="btn btn-primary" data-dismiss="modal" onclick="deleteHotelProductoOK();">Confirmar</button>
            </div>

        </div>
    </div>
 </div>
 <!--End Modal Eliminar-->

 <!--Begin Modal Activar producto-->
<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" id="modalActivateHotelProducto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">ACTIVAR PRODUCTO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                <p>¿desea activar el registro con id <span id="activate_producto_id" style="color:red;"></span> ?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btnActivateHotelProducto" class="btn btn-primary" data-dismiss="modal" onclick="activateHotelProductoOK();">Confirmar</button>
            </div>

        </div>
    </div>
 </div>
 <!--End Modal Activar producto-->

@push('scripts')
  <script>
        $(document).ready(function() {

          $('#modalViewHotelProducto').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#descripcion").focus();
          })

        });//Fin ready

        function submitFunctionHotelProducto(event) {
            storeHotelProducto();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function storeHotelProducto(){
            var p_producto=$("#descripcion").val();
            var formdata = new FormData($("#frmHotelProducto")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/hotel_producto";

            if($("#editHotelProducto").val()=="modificar"){
                url= url + "/" + $("#hotel_producto_id").val();
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
                    $("#btnGuardarHotelProducto").attr('disabled','disabled');
                    $("#btnGuardarHotelProducto").text("Procesando");
                },
                success: function(result){
                    if(result.response!="202"){  //202: producto existente
                        $("#modalViewHotelProducto").modal("hide");
                        datatable_datos.ajax.reload();//recargar registro datatables.
                        limpiarDatoHotelProducto();
                    } else {
                        messageAlert(`El producto : ${p_producto}, ya esta registrado` );
                    }

                    $("#btnGuardarHotelProducto").removeAttr('disabled');
                    $("#btnGuardarHotelProducto").text("Guardar");

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function createHotelProducto(){
           $("#editHotelProducto").val("");
           $("#title_modal_view_hotel_producto").text("NUEVO PRODUCTO");
           limpiarDatoHotelProducto();
           $('#modalViewHotelProducto').modal('show');
        }

       function editHotelProducto($this){
            limpiarDatoHotelProducto();
            var hotel_producto_id=$this.id;
            $("#editHotelProducto").val("modificar");
            $("#title_modal_view_hotel_producto").text("MODIFICAR PRODUCTO");
            $.ajax({
                type: "GET",
                url: "{{route('edithotelproducto')}}",
                data:{hotel_producto_id:hotel_producto_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $('#hotel_producto_id').val(result.hotel_producto.id);
                    $('#producto_id').val(result.producto.id);
                    $('#descripcion').val(result.producto.descripcion);
                    $('#precio').val(result.hotel_producto.precio);
                    $('#categoria_id').selectpicker('val',result.producto.categoria_id);
                    $("#categoria_id").selectpicker('refresh');
                    $("#modalViewHotelProducto").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function deleteHotelProducto($id){
            $("#delete_hotel_producto_id").text($id);
            $("#modalDeleteHotelProducto").modal("show");
        }

        function deleteHotelProductoOK(){
            var $id = $('#delete_hotel_producto_id').text();
            url=URL_BASE + "/business/hotel_producto";
            url_delete= url + "/" + $id;

            $.ajax({
                type: "POST",
                url: url_delete,
                data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(result){
                    datatable_datos.ajax.reload();
                    $("#modalDeleteHotelProducto").modal("hide");
                },
                error:function(result){

                }
            });
        }

        function activateHotelProducto($id){
            $("#activate_producto_id").text($id);
            $("#modalActivateHotelProducto").modal("show");
        }

        function activateHotelProductoOK(){
           var producto_id = $('#activate_producto_id').text();
           $.ajax({
                type: "POST",
                url: "{{route('activatehotelproducto')}}",
                data:{producto_id:producto_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    datatable_datos.ajax.reload();
                    $("#modalViewActivateHotelProducto").modal("hide");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax

        }

        function limpiarDatoHotelProducto(){
            $("#hotel_producto_id").val("");
            $("#producto_id").val("");
            $("#descripcion").val("");
            $('#categoria_id').selectpicker('val',"");
            $("#categoria_id").selectpicker('refresh');
            $("#precio").val("");
        }

  </script>

@endpush

@include('partials/utilesjs')







