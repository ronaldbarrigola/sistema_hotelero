
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewTransaccionAnticipo">
    <div class="modal-dialog">  <!--Small clase: modal-sm width: 300px  |  Por defecto clase: None width: 500px  | Large clase: modal-lg width: 800px | Extra large clase: modal-xl width: 1140px -->
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_transaccion_anticipo" class="modal-title">ANTICIPO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmTransaccionAnticipo" enctype="multipart/form-data" onsubmit="return submitTransaccionAnticipo(event)">
                    @csrf
                    @include('business/transaccion_anticipo/campos_anticipo')
                    <br>
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-success" id="btnGuardarTransaccionAnticipo" type="submit">Guardar</button>
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
            $('#modalViewTransaccionAnticipo').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
                $("#anticipo").focus();
            })
        });//Fin ready

        function submitTransaccionAnticipo(event) {
           storeTransaccionAnticipo();
           event.preventDefault(); //cancela el evento
           return false; //Cancela el envio submit para procesar por ajax
        }

        function storeTransaccionAnticipo(){
            var formdata = new FormData($("#frmTransaccionAnticipo")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/transaccion_anticipo";

            $.ajax({
                type: "POST",
                processData: false, //importante para enviar imagen
                contentType: false, //importante para enviar imagen
                enctype: 'multipart/form-data', //importante para enviar imagen
                url:url,
                data:formdata,
                dataType: 'json',
                beforeSend: function () {
                    $("#btnGuardarTransaccionAnticipo").attr('disabled','disabled');
                    $("#btnGuardarTransaccionAnticipo").html("Procesando");
                },
                success: function(result){
                    $("#modalViewTransaccionAnticipo").modal("hide");
                    $("#btnGuardarTransaccionAnticipo").removeAttr('disabled');
                    $("#btnGuardarTransaccionAnticipo").html("Guardar");
                    datatable_transaccion.ajax.reload();
                    try {
                        updateItemForId(result.transaccion.reserva_id);
                    } catch(err) {}
                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

        function createAnticipo($this){
            var transaccion_id=$this.id;
            requiredPago(false);
            requiredAnticipo(true);
            limpiarDatoTransaccionAnticipo();

            var fila=$($this).closest("tr");
            var vec_anticipo=$(fila).find("input[name='tr_anticipo[]']");
            var input_anticipo=vec_anticipo[0];
            var anticipo=$(input_anticipo).val();

            var vec_cargo=$(fila).find("input[name='tr_cargo[]']");
            var input_cargo=vec_cargo[0];
            var cargo=$(input_cargo).val();

            $("#anticipo_transaccion_id").val(transaccion_id);
            $("#anticipo").val(anticipo);
            $("#cargo").val(cargo);

            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('createanticipo')}}",
                data:{transaccion_id:transaccion_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    loadDataTransaccionAnticipoAjax(result)
                    $('#anticipo_forma_pago_id').selectpicker('val',result.forma_pago_id);
                    $("#anticipo_forma_pago_id").selectpicker('refresh');
                    $('#modalViewTransaccionAnticipo').modal('show');
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function loadDataTransaccionAnticipoAjax(result){
            $("#anticipo_forma_pago_id").find('option').remove();
            $("#anticipo_forma_pago_id").append('<option  value="">--Seleccione--</option>');
            $.each(result.formaPagos, function(i, v) {
                if(v.id!="PM"){//PM: PAGO MULTIPLE
                    $("#anticipo_forma_pago_id").append('<option  value="'+ v.id +'" >'+v.descripcion+'</option>');
                }
            });
            $("#anticipo_forma_pago_id").selectpicker('refresh');
        }

        function limpiarDatoTransaccionAnticipo(){
            $("#anticipo_transaccion_id").val("");
            $("#anticipo").val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







