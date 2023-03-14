@include('base/seguridad/usuarioRoles/adicionar')
<div id="panelUsuarioRoles">
    <div class="card-body paddingminimo">
        <button type="button"  class="btnMostrarModalRoles icon-plus btn btn-success" > ROL </button>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table id="tblUsuarioRoles" class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>ROL</th>
                            <th>Descripcion</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




@push('scripts')
    <script>
        //----variables globales----
        var global_idUsuario;
        //--------------------------
        $(document).ready(function(){
            global_idUsuario = JSON.parse("{{ json_encode($usuario_id) }}");
            if(isNaN(global_idUsuario)){
                //no es numero por tanto es un nuevo registro
                iniciarUsuarioRoles();
            }else{
                cargarUsuarioRoles(global_idUsuario);
            }
        });//fin Ready

        //=====================================================================================================================
        // iniciarUsuarioRoles (invocado al crear un nuevo Usuario)
        //=====================================================================================================================
        function iniciarUsuarioRoles(){
            $("#tblUsuarioRoles tbody tr").remove();//limpiando tabla.
            global_idUsuario='';
        }
        //=====================================================================================================================
        // cargar tabla UsuarioRoles (invocado al actualizar un Usuario existente.)
        //=====================================================================================================================
        function cargarUsuarioRoles(idUsuario){
            url=URL_BASE+'/seguridad/obtenerRolesPorIdUsuario?'+"usuarioId="+idUsuario;//Controlador UsuarioRol
            $.get(url,"",function(datosJSON){
                global_idUsuario=idUsuario;//idUsuario viene del boton que llama al formulario modal. y aqui lo convertimos en global para acceder desde cualquier parte.
                $("#tblUsuarioRoles tbody tr").remove();//limpiando tabla.
                for(i=0;i<datosJSON.length;i++){
                    fila=crearNuevaFilaRolAsignado(datosJSON[i].id,datosJSON[i].rol_id,datosJSON[i].nombre,datosJSON[i].descripcion,"guardado");
                    $("#tblUsuarioRoles tbody").append(fila);
                }
            },'json')
            .fail(function(request){console.log("error:"+request.responseText)  });
        }

        function crearNuevaFilaRolAsignado(usuario_rol_id,rol_id,rol_nom,descripcion,estado){
            fila=   "<tr>"+
                    "<td>"+
                    "     <input type='hidden' name='vec_id[]' value='"+usuario_rol_id+"'>"+
                    "     <input type='hidden' name='vec_rol_id[]' value="+rol_id+">"+
                    "     <input type='hidden' name='vec_estado[]' value='"+estado+"'>"+
                    "     <input type='text' readonly  id='vec_nombreRol[]' class='form-control' value='"+rol_nom+"'>"+
                    "</td>"+
                    "<td> <input type='text' readonly id='vec_descripcionRol[]' class='form-control' value='"+descripcion+"'></td>"+
                    "<td> <button type='button' class='btn btn-danger' onclick='eliminarRol(this);'>Eliminar</button></td>"+
                    "</tr>";
            return fila;
        }
        //=====================================================================================================================
        // eliminar Rol. si existe en DB ocultar, si es nuevo eliminar tr
        //=====================================================================================================================
        function eliminarRol(boton){

            boot4.confirm({
                msg:"Quitar Rol?",
                title:"Confirmación",
                callback:function(result){
                    if(result){
                        fila=$(boton).closest("tr");//obtiene el primer padre que sea de tipo tr
                        vec_estado=$(fila).find("input[name='vec_estado[]']");//busca en todos sus hijos el input que tenga el nombre especificado
                        input_estado=$(vec_estado[0]); //como la busqueda se realizo dentro de la fila, el vector que agarró solo tiene el elemento de la fila, por eso en el indice se pone 0
                        if($(input_estado).val() === 'guardado'){
                            $(input_estado).val('eliminado');
                            fila.hide();
                            //adicionar una fila a roles disponibles
                        }if($(input_estado).val() === 'nuevo'){
                            fila.remove();
                            //adicionar una fila a roles disponibles
                        }

                        //-----
                        vec_id=$(fila).find("input[name='vec_id[]']");//busca en todos sus hijos el input que tenga el id especificado
                        input_id=$(vec_id[0]); //como la busqueda se realizo dentro de la fila, el vector que agarró solo tiene el elemento de la fila, por eso en el indice se pone 0

                        vec_rol_id=$(fila).find("input[name='vec_rol_id[]']");
                        input_rol_id=$(vec_rol_id[0]);

                        // vec_estado=$(fila).find("input[name='vec_estado[]']");
                        // input_estado=$(vec_estado[0]);

                        vec_nombreRol=$(fila).find("input[id='vec_nombreRol[]']");
                        input_nombreRol=$(vec_nombreRol[0]);

                        vec_descripcionRol=$(fila).find("input[id='vec_descripcionRol[]']");
                        input_descripcionRol=$(vec_descripcionRol[0]);


                        filaDisponible=crearNuevaFilaRolDisponible(input_id.val().toString(),input_rol_id.val(),input_nombreRol.val(),input_descripcionRol.val(),input_estado.val());
                        $("#tblRolesDisponibles tbody").append(filaDisponible);
                    }// fin if
                }
            });

        }//fin function


    </script>
@endpush
