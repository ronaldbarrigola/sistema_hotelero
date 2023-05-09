<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewHuesped">
    <div class="modal-dialog modal-xl">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_huesped" class="modal-title">HUESPED</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmHuesped" enctype="multipart/form-data" onsubmit="return submitFormHuesped(event)">
                    @csrf
                    <input type="hidden" name="editHuesped" id="editHuesped" value="">
                    <input type="hidden" name="huesped_id" id="huesped_id" value="">

                    @include('business/huesped/detalle_huesped')

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarHuesped" type="submit">Guardar</button>
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

        }); //Fin ready

        function submitFormHuesped(event) {
            storeHuesped();
            event.preventDefault();//cancela el evento
            return false;//Cancela el envio submit para procesar por ajax
        }

        function storeHuesped(){

            var formdata = new FormData($("#frmHuesped")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/huesped";

            if($("#editHuesped").val()=="modificar"){
                url= url + "/" + $("#huesped_id").val();
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
                    $("#btnGuardarHuesped").attr('disabled','disabled');
                    $("#btnGuardarHuesped").val("Procesando");
                },
                success: function(result){
                    $("#modalViewHuesped").modal("hide");
                    $("#btnGuardarHuesped").removeAttr('disabled');
                    $("#btnGuardarHuesped").val("Guardar");
                    limpiarDatoHuesped();
                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function createHuesped(){
            $("#editHuesped").val("");
            $("#title_modal_view_huesped").text("NUEVO HUESPED");
            limpiarDatoHuesped();
            $('#modalViewHuesped').modal('show');
        }

       function editHuesped($boton){
            limpiarDatoHuesped();
            var huesped_id=$boton.id;
            $("#editHuesped").val("modificar");
            $("#title_modal_view_huesped").text("MODIFICAR HUESPED");
            $.ajax({
                type: "GET",
                url: "{{route('edithuesped')}}",
                data:{huesped_id:huesped_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    //Datos persona
                    $('#huesped_id').val(result.huesped.id);

                    $("#modalViewHuesped").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function limpiarDatoHuesped(){
            $('#huesped_id').val("");
        }

        function slideHuesped($this){
           var reserva_id=$this.id;
           huesped_reserva_id=reserva_id;
           datatable_huesped.ajax.reload();
           $('.cabecera_principal').hide();
           $('.cabecera_transaccion').hide();
           $('.cabecera_huesped').show();
           $(".carouselReserva").carousel(2);
        }

  </script>
@endpush









