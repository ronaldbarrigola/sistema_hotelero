<div class="row">
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table id="tbl_detalle_grupo" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th style="text-align:center">Reserva</th>
                    <th style="text-align:center">Hab.</th>
                    <th style="text-align:center">Opciones</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
      </div>
</div>

@push('scripts')
    <script>
        function cargarFilaGrupo(reserva_id,num_habitacion,estado){
            $('#tbl_detalle_grupo').append($('<tr>')
               .append($('<td style="text-align:center">').append('<input type="hidden" name="vec_reserva[]" value="'+reserva_id+'">'+reserva_id))
               .append($('<td style="text-align:center">').append('<input type="hidden" name="vec_num_habitacion[]" value="'+num_habitacion+'">'+num_habitacion))
               .append($('<td style="text-align:center">').append('<input type="hidden" name="vec_estado[]" value="'+estado+'"><button type="button" class="btn btn-danger" onclick="eliminarFilaGrupo(this);">Eliminar</button>'))
            );
        }

        function eliminarFilaGrupo($this){
            fila=$($this).closest("tr");//obtiene el primer padre que sea de tipo tr
            boot4.confirm({
                msg:"Quitar Reserva?",
                title:"Confirmación",
                callback:function(result){
                    if(result){
                        vec_estado=$(fila).find("input[name='vec_estado[]']");
                        input_estado=$(vec_estado[0]);
                        if($(input_estado).val() === 'guardado'){
                            $(input_estado).val('eliminado');
                            fila.hide();
                        }if($(input_estado).val() === 'nuevo'){
                            fila.remove();
                        }
                    }// fin if
                }
            });

        }//fin function

        function limpiarDatoGrupo(){
            $("#grupoId").val("");
            $("#editGrupo").val("");
            $("#nombre_grupo").val("");
            $("#color_grupo").val("");
            $("#tbl_detalle_grupo tbody tr").find('td').remove();
        }

   </script>
@endpush

