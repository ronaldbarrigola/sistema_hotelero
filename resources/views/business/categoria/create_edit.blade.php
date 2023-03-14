
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewCategoria">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_categoria" class="modal-title">INFORMACION CATEGORIA</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmCategoria" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="edit" id="edit" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS CATEGORIA</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/categoria/campos_categoria')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarCategoria" type="button">Guardar</button>
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

          $(document).on("click", "#btnGuardarCategoria", function(){
             guardarCategoria();
          });

          $('#modalViewCategoria').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#descripcion").focus();
          })

        });//Fin ready

        function guardarCategoria(){
            var descripcion=$("#descripcion").val();

            if(descripcion==""||descripcion==null){
                messageAlert('Debe introducir descripcion');
                return 0;
            }

            var formdata = new FormData($("#frmCategoria")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/categoria";

            if($("#edit").val()=="modificar"){
                url= url + "/" + $("#categoria_id").val();
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
                    $("#btnGuardarCategoria").attr('disabled','disabled');
                    $("#btnGuardarHCategoria").text("Procesando");
                },
                success: function(result){

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewCategoria").modal("hide");
                    $("#btnGuardarCategoria").removeAttr('disabled');
                    $("#btnGuardarCategoria").text("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                    limpiarDatoCategoria();
                }//END complete

            }); //End Ajax
       }

       function dataEditCategoria($boton){
            limpiarDatoCategoria();
            var categoria_id=$boton.id;
            $("#edit").val("modificar");
            $("#title_modal_view_categoria").text("MODIFICAR CATEGORIA");
            $.ajax({
                type: "GET",
                url: "{{route('editcategoria')}}",
                data:{categoria_id:categoria_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                     $('#categoria_id').val(result.categoria.id);
                     $('#descripcion').val(result.categoria.descripcion);
                     $("#modalViewCategoria").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function limpiarDatoCategoria(){
            $("#descripcion").val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







