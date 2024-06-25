<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewGrupo">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_grupo" class="modal-title">AGRUPAR</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmGrupo" enctype="multipart/form-data" onsubmit="return submitGrupo(event)">
                    @csrf

                    <input type="hidden" name="edit" id="edit" value="">

                    @include('business/grupo/campos_grupo')
                    @include('business/grupo/detalle_grupo')

                    <br>

                    <div class="d-flex justify-content-around">
                        <button class="btn btn-success" id="btnGuardarGrupo" type="submit">Guardar</button>
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

          $('#modalViewGrupo').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#nombre_grupo").focus();
          })

        });//Fin ready

        function submitGrupo(event) {
            guardarGrupo();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function guardarGrupo(){
            var formdata = new FormData($("#frmGrupo")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/grupo";

            $.ajax({
                type: "POST",
                processData: false, //importante para enviar imagen
                contentType: false, //importante para enviar imagen
                enctype: 'multipart/form-data', //importante para enviar imagen
                url:url,
                data:formdata,
                dataType: 'json',
                beforeSend: function () {
                    $("#btnGuardarGrupo").attr('disabled','disabled');
                    $("#btnGuardarGrupo").text("Procesando");
                },
                success: function(result){
                    if(result.response){
                        limpiarDatoGrupo();
                        $.each(result.lista_reserva,function(i,reserva_id) {
                            console.log(reserva_id);
                            updateItemForId(reserva_id);
                        });
                    } else {
                        messageAlert(result.message);
                    }

                    $("#btnGuardarGrupo").removeAttr('disabled');
                    $("#btnGuardarGrupo").text("Guardar");

                    $("#modalViewGrupo").modal("hide");

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

  </script>

@endpush

@include('partials/utilesjs')







