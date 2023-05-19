
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalViewHabitacion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_habitacion" class="modal-title">INFORMACION HABITACION</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmHabitacion" enctype="multipart/form-data" onsubmit="return submitFunction(event)">
                    @csrf

                    <input type="hidden" name="edit" id="edit" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS HABITACION</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/habitacion/campos_habitacion')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarHabitacion" type="submit">Guardar</button>
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
          $('#modalViewHabitacion').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#num_habitacion").focus();
          })

        });//Fin ready

        function submitFunction(event) {
            guardarHabitacion();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function guardarHabitacion(){

            var formdata = new FormData($("#frmHabitacion")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/habitacion";

            if($("#edit").val()=="modificar"){
                url= url + "/" + $("#habitacion_id").val();
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
                    $("#btnGuardarHabitacion").attr('disabled','disabled');
                    $("#btnGuardarHabitacion").text("Procesando");
                },
                success: function(result){

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }, //END error
                complete:function(result, textStatus ){
                    $("#modalViewHabitacion").modal("hide");
                    $("#btnGuardarHabitacion").removeAttr('disabled');
                    $("#btnGuardarHabitacion").text("Guardar");
                    datatable_datos.ajax.reload();//recargar registro datatables.
                    limpiarDatoHabitacion();
                }//END complete

            }); //End Ajax
       }

       function dataEditHabitacion($boton){
            limpiarDatoHabitacion();
            var habitacion_id=$boton.id;
            $("#edit").val("modificar");
            $("#title_modal_view_habitacion").text("MODIFICAR HABITACION");
            $.ajax({
                type: "GET",
                url: "{{route('edithabitacion')}}",
                data:{habitacion_id:habitacion_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){

                },//End success
                complete:function(result, textStatus ){
                     var data=result.responseJSON;
                     $('#habitacion_id').val(data.habitacion.id);
                     $('#num_habitacion').val(data.habitacion.num_habitacion);
                     $('#descripcion').val(data.habitacion.descripcion);
                     $('#piso').val(data.habitacion.piso);
                     $('#precio').val(data.habitacion.precio);
                     $('#tipo_habitacion_id').selectpicker('val', data.habitacion.tipo_habitacion_id);
                     $("#tipo_habitacion_id").selectpicker('refresh');
                     $('#estado_habitacion_id').selectpicker('val',data.habitacion.estado_habitacion_id);
                     $("#estado_habitacion_id").selectpicker('refresh');
                     $("#modalViewHabitacion").modal("show");
                }
            }); //End Ajax
        }

        function limpiarDatoHabitacion(){
            $("#num_habitacion").val("");
            $("#piso").val("");
            $("#descripcion").val("");
            $("#precio").val("");
            $("#tipo_habitacion_id").selectpicker("val","");
            $("#tipo_habitacion_id").selectpicker("refresh");
            $('#estado_habitacion_id').selectpicker('val',"");
            $("#estado_habitacion_id").selectpicker('refresh');
        }

  </script>
@endpush

@include('partials/utilesjs')







