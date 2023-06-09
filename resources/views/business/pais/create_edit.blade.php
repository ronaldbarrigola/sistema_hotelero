
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewPais">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_pais" class="modal-title">INFORMACION PAIS</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="frmPais" enctype="multipart/form-data" onsubmit="return submitPais(event)">
                    @csrf

                    <input type="hidden" name="editPais" id="editPais" value="">

                    <div class="panel_ordenante card">
                        <div class="card-header py-0">
                            <strong>DATOS PAIS</strong>
                        </div>
                        <div class="card-body py-0">
                            @include('business/pais/campos_pais')
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4 offset-md-4 d-flex justify-content-between">
                            <button class="btn btn-success" id="btnGuardarPais" type="submit">Guardar</button>
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

          $('#modalViewPais').on('shown.bs.modal', function() {//Para enfocar input de un formulario modal
             $("#descripcion").focus();
          })

        });//Fin ready

        function submitPais(event) {
            storePais();
            event.preventDefault(); //cancela el evento
            return false; //Cancela el envio submit para procesar por ajax
        }

        function storePais(){
            var p_pais=$("#descripcion").val();
            var formdata = new FormData($("#frmPais")[0]); //Serializa con imagenes multimedia
            url=URL_BASE + "/business/pais";

            if($("#editPais").val()=="modificar"){
                url= url + "/" + $("#pais_id").val();
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
                    $("#btnGuardarPais").attr('disabled','disabled');
                    $("#btnGuardarPais").text("Procesando");
                },
                success: function(result){
                    if(result.response!="202"){  //202: producto existente
                        $("#modalViewPais").modal("hide");
                        datatable_datos.ajax.reload();//recargar registro datatables.
                        limpiarDatoPais();
                    } else {
                        messageAlert(`El pais : ${p_pais}, ya esta registrado` );
                    }

                    $("#btnGuardarPais").removeAttr('disabled');
                    $("#btnGuardarPais").text("Guardar");

                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }, //END error
                complete:function(result, textStatus ){

                }//END complete

            }); //End Ajax
       }

       function editPais($id){
            limpiarDatoPais();
            var pais_id=$id;
            $("#editPais").val("modificar");
            $("#title_modal_view_pais").text("MODIFICAR PAIS");
            $.ajax({
                type: "GET",
                url: "{{route('editpais')}}",
                data:{pais_id:pais_id,'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $('#pais_id').val(result.pais.id);
                    $('#descripcion').val(result.pais.descripcion);
                    $('#dominio').val(result.pais.dominio);
                    $("#modalViewPais").modal("show");
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function limpiarDatoPais(){
            $("#descripcion").val("");
            $("#dominio").val("");
        }

  </script>

@endpush

@include('partials/utilesjs')







