<div class="modal  fade modal-slide-in-right"  data-backdrop="static" data-keyboard="false" aria-hidden="true"
    role="dialog" tabindex="-1" id="modalRegistro">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header draggable paddingminimo">
                <h5 id="tituloRegistro" class="modal-title"> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body paddingminimo">
                <div class="card boder border-dark">
                    <div class="card-body paddingminimo">
                        <!-- =============================================================================================================== -->
                        <!-- FORMULARIO DE REGISTRO DE DATOS -->
                        <!-- =============================================================================================================== -->
                        <form id='frmDatosRegistro' autocomplete="off" >
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="codigo" class="my-0"><strong>Código:</strong></label>
                                        <input type="text" id="codigo" name="codigo" required class="form-control" placeholder="Código">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="nombre" class="my-0"><strong>Nombre:</strong></label>
                                        <input type="text" id="nombre" name="nombre" required  class="form-control" placeholder="Nombre">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="descripcion" class="my-0"><strong>Descripción:</strong></label>
                                        <input type="text" id="descripcion" name="descripcion" required class="form-control" placeholder="Descripción">
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>


            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button id="btnGuardar" form="frmDatosRegistro" class="btn btn-success" data-textoprocesando="Guardando..." type="submit">Guardar</button>
                <button id="btnVolver" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Volver</button>
            </div>
        </div>
    </div>

</div>



@push('scripts')
    <script>
        //variables globales-----------------------------------------
        var idModelo='';
        //====== READY ===============================================
        $(document).ready(function(){
            //=====================================================================================================================
            //Mostrando Modal Nuevo Registro
            //=====================================================================================================================
            $(".btnMostrarModalNuevo").on("click",function(){
                //limpiarMensajesError();
                idModelo='';//aun no exite el modelo portanto no existe id
                $('#frmDatosRegistro')[0].reset();//limpiando todos los campos de texto.
                $("#tituloRegistro").html("CREAR REGISTRO");
                $('#modalRegistro').modal('show');
            });

            //=====================================================================================================================
            //Mostrando Modal Modifiicación Registro
            //=====================================================================================================================
            $('table').on( "click",'.btnMostrarModalModificar', function() {
                idModelo=$(this).data('idmodelo'); //id del registro que se esta modificando
                cargarRol(idModelo);
                $("#tituloRegistro").html("MODIFICAR REGISTRO");
                $('#modalRegistro').modal('show');
            });


            //=====================================================================================================================
            //Cargando el pedido en formulario Modal, por AJAX
            //=====================================================================================================================
            function cargarRol(idModelo){
                url=URL_BASE+'/seguridad/roles/'+idModelo+"/edit";
                $.get(url,"",function(JsonDato){
                    $("#codigo").val(JsonDato.codigo);
                    $("#nombre").val(JsonDato.nombre);
                    $("#descripcion").val(JsonDato.descripcion);
                },'json');
            }

            //=====================================================================================================================
            //ENVIANDO FORMULARIO AL SERVIDOR
            //=====================================================================================================================
            $('#frmDatosRegistro').on('submit', function (event) {
                event.preventDefault();//evitando realizar submit (con recarga de pagina) para realizar manualmente con json.
                event.stopPropagation();
                form=$(this);
                var formData = new FormData(form[0]);//contiene todos los datos de los controles dentro el formulario
                var url=URL_BASE+"/seguridad/roles";
                if(idModelo==''){
                    //insertar
                }else{
                    //modificar
                    url = url+"/"+idModelo;
                    formData.append('_method', 'patch'); //UPDATE
                }
                activarEstadoProcesando("#modalRegistro","#btnGuardar");
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,// formData ya tiene el "csrf-token"
                    processData: false,//PARA SUBIR ARCHIVO
                    contentType: false,//PARA SUBIR ARCHIVO
                    dataType: 'json',
                    success: function(JsonDato){
                    },

                    error:function(jqXHR, textStatus, errorThrown){
                        if (jqXHR.status === 0) {
                            alert('No conectado: Verificar Red');
                        } else if (jqXHR.status == 404) {
                            alert('Pagina no encontrada [404].');
                        } else if (jqXHR.status == 500) {
                            //alert('Error Interno del Servidor [500]:' +jqXHR.responseJSON.message);
                            alert('Error Interno del Servidor [500]:' +jqXHR.responseText);
                        } else if (textStatus === 'parsererror') {
                            alert('Requested JSON parse failed.');
                        } else if (textStatus === 'timeout') {
                            alert('Time out error.');
                        } else if (textStatus === 'abort') {
                            alert('Solicitud Ajax Abortada');
                        } else {
                            alert('Error no Capturado: ' + jqXHR.responseText);
                        }
                    },
                    complete:function(jqXHR, textStatus ){
                        //completado correctamente
                        //console.log(jqXHR.responseJSON.id);
                        if(jqXHR.status===200){
                            //location.reload();//recarga la pagina actual.
                            desactivarEstadoProcesando("#modalRegistro","#btnGuardar");
                            $('#tblListaDatos').DataTable().ajax.reload();//recargar registro datatables.
                            $("#modalRegistro").modal("hide");
                            toastr.success('Se guardo correctamente','Rol con ID:'+jqXHR.responseJSON.id);
                        }
                    }

                });
                return true;
            });
            //------------------------------------------------------------------------------------------------------------------------
        });//FIN READY ready
        //============================================================================================================================
    </script>
@endpush
