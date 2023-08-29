
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewProducto">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_producto" class="modal-title">INFORMACION PRODUCTO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmProducto" enctype="multipart/form-data" onsubmit="return submitFunction(event)">
                    @csrf

                    <input type="hidden" name="edit" id="edit" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS PRODUCTO</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/producto/campos_producto')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarProducto" type="submit">Guardar</button>
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

          $('#modalViewProducto').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#descripcion").focus();
          })

        });//Fin ready

        function submitFunction(event) {
            guardarProducto();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function guardarProducto(){
            var p_producto=$("#descripcion").val();
            var formdata = new FormData($("#frmProducto")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/producto";

            if($("#edit").val()=="modificar"){
                url= url + "/" + $("#producto_id").val();
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
                    $("#btnGuardarProducto").attr('disabled','disabled');
                    $("#btnGuardarProducto").text("Procesando");
                },
                success: function(result){
                    if(result.response!="202"){  //202: producto existente
                        $("#modalViewProducto").modal("hide");
                        datatable_datos.ajax.reload();//recargar registro datatables.
                        limpiarDatoProducto();
                    } else {
                        messageAlert(`El producto : ${p_producto}, ya esta registrado` );
                    }

                    $("#btnGuardarProducto").removeAttr('disabled');
                    $("#btnGuardarProducto").text("Guardar");

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function dataEditProducto($boton){
            limpiarDatoProducto();
            var producto_id=$boton.id;
            $("#edit").val("modificar");
            $("#title_modal_view_producto").text("MODIFICAR PRODUCTO");
            $.ajax({
                type: "GET",
                url: "{{route('editproducto')}}",
                data:{producto_id:producto_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $('#producto_id').val(result.producto.id);
                    $('#descripcion').val(result.producto.descripcion);
                    $('#categoria_id').selectpicker('val',result.producto.categoria_id);
                    $("#categoria_id").selectpicker('refresh');
                    $("#modalViewProducto").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function limpiarDatoProducto(){
            $("#descripcion").val("");
            $('#categoria_id').selectpicker('val',"");
            $("#categoria_id").selectpicker('refresh');
        }

  </script>

@endpush

@include('partials/utilesjs')







