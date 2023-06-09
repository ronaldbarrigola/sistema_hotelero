
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewCiudad">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_ciudad" class="modal-title">INFORMACION CIUDAD</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmCiudad" enctype="multipart/form-data" onsubmit="return submitCiudad(event)">
                    @csrf

                    <input type="hidden" name="editCiudad" id="editCiudad" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS CIUDAD</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/ciudad/campos_ciudad')
                        </div>
                    </div>

                    <br>

                    <div class="d-flex justify-content-around">
                        <button class="btn btn-success" id="btnGuardarCiudad" type="submit">Guardar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
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
          $('#modalViewCiudad').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#pais_id").focus();
          })
        });//Fin ready

        function submitCiudad(event) {
            storeCiudad();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function storeCiudad(){
            var p_ciudad=$("#descripcion").val();
            var formdata = new FormData($("#frmCiudad")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/ciudad";

            if($("#editCiudad").val()=="modificar"){
                url= url + "/" + $("#ciudad_id").val();
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
                    $("#btnGuardarCiudad").attr('disabled','disabled');
                    $("#btnGuardarCiudad").text("Procesando");
                },
                success: function(result){
                    if(result.response!="202"){  //202: producto existente
                        $("#modalViewCiudad").modal("hide");
                        datatable_datos.ajax.reload();//recargar registro datatables.
                        limpiarDatoCiudad();
                    } else {
                        messageAlert(`La ciudad : ${p_ciudad}, ya esta registrado en el pais seleccionado` );
                    }

                    $("#btnGuardarCiudad").removeAttr('disabled');
                    $("#btnGuardarCiudad").text("Guardar");

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function editCiudad($this){
            limpiarDatoCiudad();
            var ciudad_id=$this.id;
            $("#editCiudad").val("modificar");
            $("#title_modal_view_ciudad").text("MODIFICAR CIUDAD");
            $.ajax({
                type: "GET",
                url: "{{route('editciudad')}}",
                data:{ciudad_id:ciudad_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                     $('#ciudad_id').val(result.ciudad.id);
                     $('#pais_id').selectpicker('val',result.ciudad.pais_id);
                     $("#pais_id").selectpicker('refresh');
                     $('#descripcion').val(result.ciudad.descripcion);
                     $("#modalViewCiudad").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function limpiarDatoCiudad(){
            $("#ciudad_id").val("");
            $("#descripcion").val("");
            $("#pais_id").selectpicker("val","");
            $("#pais_id").selectpicker("refresh");
        }

  </script>
@endpush

@include('partials/utilesjs')







