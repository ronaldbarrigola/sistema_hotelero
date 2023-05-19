
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewTipoHabitacion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_tipo_habitacion" class="modal-title">INFORMACION TIPO HABITACION</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmTipoHabitacion" enctype="multipart/form-data" onsubmit="return submitFunctionTipoHabitacion(event)">
                    @csrf
                    <input type="hidden" name="editTipoHabitacion" id="editTipoHabitacion" value="">
                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS TIPO HABITACION</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/tipo_habitacion/campos_tipo_habitacion')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarTipoHabitacion" type="submit">Guardar</button>
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
          $('#modalViewTipoHabitacion').on('shown.bs.modal', function() {
             $("#codigo").focus();
          })

        });//Fin ready

        function submitFunctionTipoHabitacion(event) {
            storeTipoHabitacion();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function createTipoHabitacion(){
            $("#editTipoHabitacion").val("");
            $("#title_modal_view_tipo_habitacion").text("NUEVO TIPO HABITACION");
            limpiarDatoTipoHabitacion();
            $('#modalViewTipoHabitacion').modal('show');
        }

        function storeTipoHabitacion(){

            var formdata = new FormData($("#frmTipoHabitacion")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/tipo_habitacion";

            if($("#editTipoHabitacion").val()=="modificar"){
                url= url + "/" + $("#tipo_habitacion_id").val();
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
                    $("#btnGuardarTipoHabitacion").attr('disabled','disabled');
                    $("#btnGuardarTipoHabitacion").text("Procesando");
                },
                success: function(result){

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewTipoHabitacion").modal("hide");
                    $("#btnGuardarTipoHabitacion").removeAttr('disabled');
                    $("#btnGuardarTipoHabitacion").text("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                    limpiarDatoTipoHabitacion();
                }//END complete

            }); //End Ajax
       }

       function editTipoHabitacion($this){
            limpiarDatoTipoHabitacion();
            var tipo_habitacion_id=$this.id;
            $("#editTipoHabitacion").val("modificar");
            $("#title_modal_view_tipo_habitacion").text("MODIFICAR TIPO HABITACION");
            $.ajax({
                type: "GET",
                url: "{{route('edittipohabitacion')}}",
                data:{tipo_habitacion_id:tipo_habitacion_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $('#tipo_habitacion_id').val(result.tipo_habitacion.id);
                    $('#codigo').val(result.tipo_habitacion.codigo);
                    $('#descripcion').val(result.tipo_habitacion.descripcion);
                    $("#modalViewTipoHabitacion").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function limpiarDatoTipoHabitacion(){
            $("#codigo").val("");
            $("#descripcion").val("");
        }

  </script>
@endpush

@include('partials/utilesjs')







