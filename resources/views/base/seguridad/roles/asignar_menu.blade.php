<div class="modal  fade modal-slide-in-right"  data-backdrop="static" data-keyboard="false" aria-hidden="true"
    role="dialog" tabindex="-1" id="modalAsignarMenu">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header draggable paddingminimo">
                <h5 id="tituloAsignarMenu" class="modal-title"> </h5>
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
                        <form id='frmAsignarMenu' autocomplete="off" >
                            {{-- {{Form::token()}} --}}
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" name="rol_id" id="rol_id" value="">
                                    <div class="css-treeview" >
                                        <ul id="lista_menu">
                                        </ul>
                                    </div>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>


            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button id="btnGuardar" form="frmAsignarMenu" class="btn btn-success" data-textoprocesando="Guardando..." type="submit">Guardar</button>
                <button id="btnVolver" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Volver</button>
            </div>
        </div>
    </div>

</div>


@push('estilos')
    <link rel="stylesheet" href="{{asset('css/treeview.css')}}">
@endpush
@push('scripts')
    <script>
        //====== READY ===============================================
        $(document).ready(function(){
            // //=====================================================================================================================
            // //Mostrando Modal Nuevo Registro
            // //=====================================================================================================================
            // $('table').on( "click",'.btnMostrarModalModificar', function() {
            //     //limpiarMensajesError();
            //     console.log("asig");
            //     idModelo='';//aun no exite el modelo portanto no existe id
            //     $('#frmAsignarMenu')[0].reset();//limpiando todos los campos de texto.
            //     $("#tituloAsignarMenu").html("ASIGNAR MENUS");
            //     $('#modalAsignarMenu').modal('show');
            // });

            //=====================================================================================================================
            //Mostrando SELECTOR DE MENUS
            //=====================================================================================================================
            $('table').on( "click",'.btnMostrarModalAsignarMenu', function() {
                $('#frmAsignarMenu')[0].reset();//limpiando todos los campos de texto.
                $('#lista_menu').find('*').remove();// limpiando contenido de lista.
                var idModelo=$(this).data('idmodelo'); //id del registro que se esta modificando
                var rolNombre=$(this).data('rolnombre'); //id del registro que se esta modificando
                cargarListaMenus(idModelo);
                $("#tituloAsignarMenu").html("SELECCIONAR MENUS PARA ROL:"+rolNombre);
                $('#modalAsignarMenu').modal('show');
            });


            //=====================================================================================================================
            //Cargando lista de menus por AJAX
            //=====================================================================================================================
            function cargarListaMenus(idModelo){
                url=URL_BASE+"/seguridad/rolmenu/asignacion_menus";
                $("#rol_id").val(idModelo);
                $.get(url,"idRol="+idModelo,function(JsonDato){
                    //$(lista).find("li").remove();//limpiando tabla.
                    var menuRaiz={"id": null, "nombre": "MENU","asignado":0};//creando en nodo raiz, con nombre menu
                    li=generarMenusRecursivo(JsonDato,menuRaiz);
                    $("#lista_menu").html(li);
                },'json');
            }
            //=====================================================================================================================
            // GENERANDO LISTA DE MENU EN TREEVIEW CON CSS RECURSIVAMENTE
            //=====================================================================================================================
            function generarMenusRecursivo(JsonDato,menuPadre){
                //filtrando submenus
                var subMenus=$.grep(JsonDato,function(item,index){
                    return item.padre_id==menuPadre.id;
                });
                var marcado=menuPadre.asignado==1?'checked':'';
                var visibleClass=menuPadre.id==null?'d-none':'';
                var botonMasMenos='';
                var nodo='<span> <input type="checkbox" '+marcado+' class="mx-2 '+visibleClass+'">'+
                            '<i class="'+menuPadre.icono+'"> </i> '+menuPadre.nombre+
                            '<input type="hidden"  name="vec_asignado[]" value='+menuPadre.asignado+'>'+
                            '<input type="hidden"  name="vec_menu_id[]" value="'+menuPadre.id+'">'+
                        '</span>';
                var lista="";
                if(subMenus.length==0){
                    lista= "<li> "+nodo+"</li>";
                }else{
                    botonMasMenos='<span class="bg-primary text-white" data-toggle="collapse" data-target="#submenu_'+menuPadre.id+'"  aria-expanded="true" aria-controls="submenu_'+menuPadre.id+'">'+
                                        '<i class="collapsed icon-plus"></i>'+
                                        '<i class="expanded icon-minus"></i>'+
                                   '</span>';
                    lista=lista+'<li> '+botonMasMenos+' '+nodo+' <ul class="collapse show" id="submenu_'+menuPadre.id+'">';
                    for(var i=0;i<subMenus.length;i++){
                        lista=lista+ generarMenusRecursivo(JsonDato,subMenus[i]);
                    }// fin for
                    lista=lista+"</ul></li>";
                }
                return lista;
            }//fin funcion

            // $(".css-treeview").on("click","input[type='checkbox']",function(){
            //     alert($(this).val());
            // });

            //=====================================================================================================================
            // ACTUALIZANDO VALOR DE INPUT ASIGNADO EN BASE AL MARCADO O DESCMARCADO DEL CHECKBOX
            //=====================================================================================================================
            $('#lista_menu').on( "click",'input[type=checkbox]', function() {
                // $('#textbox1').val($(this).is(':checked'));
                var input_checkbox=$(this);
                var input_asignado=input_checkbox.parent().find('input[name="vec_asignado[]"]');
                var valor=input_checkbox.is(':checked')?1:0;
                input_asignado.val(valor);
                //console.log(input_asignado.val());
            });

            //=====================================================================================================================
            //ENVIANDO FORMULARIO AL SERVIDOR
            //=====================================================================================================================
            $('#frmAsignarMenu').on('submit', function (event) {
                event.preventDefault();event.stopPropagation();//evitando realizar submit (con recarga de pagina) para realizar manualmente con json.
                form=$(this);
                var formData = new FormData(form[0]);//contiene todos los datos de los controles dentro el formulario
                //$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                var url=URL_BASE+"/seguridad/rolmenu/guardar_asignacion_menus";
                activarEstadoProcesando("#modalAsignarMenu","#btnGuardar");
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,// formData ya tiene el "csrf-token"
                    // data:{ "_token": "{{ csrf_token() }}",
                    //        "rol_id": $("#rol_id").val(),
                    //         'vec_asignado[]': $('[name="vec_asignado[]"]').serializeArray(),
                    //         'vec_menu_id[]':$('[name="vec_menu_id[]"]').serializeArray(),

                    // },
                     processData: false,//PARA SUBIR ARCHIVO
                     contentType: false,
                     datatype : "json",
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
                        if(jqXHR.status===200){
                            //location.reload();//recarga la pagina actual.
                            desactivarEstadoProcesando("#modalAsignarMenu","#btnGuardar");
                            //$('#tblListaDatos').DataTable().ajax.reload();//recargar registro datatables.
                            $("#modalAsignarMenu").modal("hide");
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
