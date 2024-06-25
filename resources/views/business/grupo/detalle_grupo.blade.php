<div class="row">
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table id="tbl_detalle_grupo" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th style="text-align:center">Nro Reserva</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
      </div>
</div>

@push('scripts')
    <script>
        function cargarFilaGrupo(reserva_id,estado){
            $('#tbl_detalle_grupo').append($('<tr>')
               .append($('<td style="text-align:center">').append('<input type="hidden" name="vec_estado[]" value="'+estado+'"><input type="hidden" name="vec_reserva[]" value="'+reserva_id+'">'+reserva_id))
            );
        }

        function limpiarDatoGrupo(){
            $("#nombre_grupo").val("");
            $("#tbl_detalle_grupo tbody tr").find('td').remove();
        }

   </script>
@endpush

