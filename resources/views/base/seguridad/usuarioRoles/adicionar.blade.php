
<!-- ================================================================================================================= -->
<!--  Modal para adicionar Rol -->
<!-- ================================================================================================================= -->
<div class="modal fade modal-slide-in-right" aria-hidden="true"
     role="dialog" tabindex="-1" id="modalRol">

   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header draggable">
                <h5 class="modal-title">Rol</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">X</span>
                </button>
           </div>


           <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="table-responsive">
                            <table id="tblRolesDisponibles" class="table table-striped table-bordered table-sm table-hover">
                                <thead>
                                    <th>Rol</th>
                                    <th>Descripcion</th>
                                    <th>Adicionar</th>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
           </div>

           <div class="modal-footer d-flex justify-content-around">
                <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">Volver</button>
            </div>

       </div>
   </div>
</div>


<!-- ================================================================================================================= -->
<!-- Script  -->
<!-- ================================================================================================================= -->
@include('partials/utilesjs')
@push('scripts')
    <script>
        // var urlBase;
        $(document).ready(function(){
            //  urlBase=window.location.origin;
            // urlPublica=$("#urlPublica").val();

            cargarListaRolesDisponibles();

            //__ click mostrar modal nota_______________________________
            $(".btnMostrarModalRoles").click(function(){
                $("#modalRol").modal("show");
            });

        });//fin Ready

    //=====================================================================================================================
    // cargar Lista de Roles Disponibles los que se pueden asignar al usuario
    //=====================================================================================================================
    function cargarListaRolesDisponibles(){
        url=URL_BASE+'/seguridad/obtenerRolesFaltantesPorIdUsuario';//ejecuta metodo del controlador UsuarioRolController
        idUsuario=JSON.parse("{{ json_encode($usuario_id) }}");//$usuario_id viene como parametro desde el modulo llamador
        $.get(url,"usuarioId="+idUsuario,function(datosJSON){
            //console.log(datosJSON);
            //$("#tblColores tbody tr").remove();
             for(i=0;i<datosJSON.length;i++){
                 fila=crearNuevaFilaRolDisponible('',datosJSON[i].rol_id,datosJSON[i].nombre,datosJSON[i].descripcion,"nuevo");
                 $("#tblRolesDisponibles tbody").append(fila);
             }
        },"json")
        .fail(function(request){console.log("error:"+request.responseText)  });
    }

    //=====================================================================================================================
    // crear nueva fila de rol disponible para adicionar
    //=====================================================================================================================
    function crearNuevaFilaRolDisponible(usuario_rol_id,rol_id,rol_nom,descripcion,estado){
        fila=   "<tr>"+
                    "<td>"+
                    "     <input type='hidden' name='vec_id2[]' value='"+usuario_rol_id+"'>"+
                    "     <input type='hidden' name='vec_rol_id2[]' value="+rol_id+">"+
                    "     <input type='hidden' name='vec_estado2[]' value='"+estado+"'>"+
                    "     <input type='text' readonly id='vec_nombreRol2[]'  class='form-control' value='"+rol_nom+"'>"+
                    "</td>"+
                    "<td> <input type='text' readonly id='vec_descripcionRol2[]' class='form-control' value='"+descripcion+"'></td>"+
                    "<td> <button type='button' class='btn btn-primary' onclick='agregarRol(this);'>Agregar</button></td>"+
                "</tr>";
            return fila;
        }



    //=====================================================================================================================
    // adicionar Rol
    //=====================================================================================================================
    function agregarRol(boton){
        //fila=$(boton).parent().parent().html();
        //alert(fila.html());
        fila=$(boton).closest("tr");//obtiene el primer padre que sea de tipo tr
        vec_id2=$(fila).find("input[name='vec_id2[]']");//busca en todos sus hijos el input que tenga el id especificado
        input_id2=$(vec_id2[0]); //como la busqueda se realizo dentro de la fila, el vector que agarró solo tiene el elemento de la fila, por eso en el indice se pone 0

        vec_rol_id2=$(fila).find("input[name='vec_rol_id2[]']");
        input_rol_id2=$(vec_rol_id2[0]);

        vec_estado2=$(fila).find("input[name='vec_estado2[]']");
        input_estado2=$(vec_estado2[0]);

        vec_nombreRol2=$(fila).find("input[id='vec_nombreRol2[]']");
        input_nombreRol2=$(vec_nombreRol2[0]);

        vec_descripcionRol2=$(fila).find("input[id='vec_descripcionRol2[]']");
        input_descripcionRol2=$(vec_descripcionRol2[0]);


        if(input_estado2.val()==='eliminado'){
            input_estado2.val('guardado');//si el estado es eliminado, significa que existia en la base de datos, por tanto al agregar de nuevo el estado sera guardado... para hacer solo un update
        }
        else{
            input_estado2.val('nuevo');//si es nuevo, quiere decir que no existe en la base de datos, por tanto se hara un insert
        }


        fila.remove();
        filaAsignada=crearNuevaFilaRolAsignado(input_id2.val().toString(),input_rol_id2.val(),input_nombreRol2.val(),input_descripcionRol2.val(),input_estado2.val());
        $("#tblUsuarioRoles tbody").append(filaAsignada);

        // $("#modalRol").modal("hide");
    }
    //=====================================================================================================================

    </script>
@endpush
