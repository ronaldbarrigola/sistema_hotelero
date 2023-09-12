<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewProfesion">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_profesion" class="modal-title">INFORMACION PROFESION</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="frmProfesion" enctype="multipart/form-data" onsubmit="return submitProfesion(event)">
                    @csrf
                    <input type="hidden" name="editProfesion" id="editProfesion" value="">
                    @include('business/profesion/campos_profesion')
                    <br>
                    <div class="d-flex justify-content-around">
                        <button class="btn btn-success" id="btnGuardarProfesion" type="submit">Guardar</button>
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
        var internalExecution=true;
        $(document).ready(function() {

          $('#modalViewProfesion').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#descripcion").focus();
          })

        });//Fin ready

        function submitProfesion(event) {
            storeProfesion();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function createProfesion(parameter){
            internalExecution=parameter;
            $("#editProfesion").val("");
            $("#title_modal_view_profesion").text("NUEVA PROFESION");
            limpiarDatoProfesion();
            $('#modalViewProfesion').modal('show');
        }

        function storeProfesion(){
            var p_profesion=$("#descripcion").val();
            var formdata = new FormData($("#frmProfesion")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/profesion";

            if($("#editProfesion").val()=="modificar"){
                url= url + "/" + $("#profesionId").val();
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
                    $("#btnGuardarProfesion").attr('disabled','disabled');
                    $("#btnGuardarProfesion").text("Procesando");
                },
                success: function(result){
                    if(result.response!="202"){//202: producto existente
                        $("#modalViewProfesion").modal("hide");
                        if(internalExecution){
                            datatable_datos.ajax.reload();
                        } else {
                            loadDataProfesionesAjax(result.profesion.id);
                        }
                        limpiarDatoProfesion();
                    } else {
                        messageAlert(`La profesion : ${p_profesion}, ya esta registrado` );
                    }

                    $("#btnGuardarProfesion").removeAttr('disabled');
                    $("#btnGuardarProfesion").text("Guardar");

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function editProfesion($id){
            limpiarDatoProfesion();
            var profesion_id=$id;
            $("#editProfesion").val("modificar");
            $("#title_modal_view_profesion").text("MODIFICAR PROFESION");
            $.ajax({
                type: "GET",
                url: "{{route('editprofesion')}}",
                data:{profesion_id:profesion_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $('#profesionId').val(result.profesion.id);
                    $('#descripcion').val(result.profesion.descripcion);
                    $("#modalViewProfesion").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function loadDataProfesionesAjax(profesion_id){
            $.ajax({
                type: "GET",
                url: "{{route('obtener_profesiones')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                success: function(result){
                    $("#profesion_id").find('option').remove();
                    $("#profesion_id").append('<option  value="">--Seleccione--</option>');
                    $.each(result.profesiones, function(i, v) {
                        $("#profesion_id").append('<option  value="' + v.id + '" >' + v.descripcion + '</option>');
                    });
                    $("#profesion_id").selectpicker("refresh");
                    $("#profesion_id").selectpicker("val",profesion_id);
                    $("#profesion_id").selectpicker("refresh");
                }//End success
            }); //End Ajax
        }

        function limpiarDatoProfesion(){
            $("#profesionId").val("");
            $("#descripcion").val("");
        }

  </script>

@endpush

@include('partials/utilesjs')







