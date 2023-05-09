<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-12">
        <div class="form-group">
            <label for="huesped_persona_id" class="my-0" ><strong>Huesped:</strong></label>
            <select id="huesped_persona_id" name="huesped_persona_id" class="form-control selectpicker" data-live-search="true">
                {{-- Datos cargados mediante ajax --}}
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <table id="tbl_detalle_huesped" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th style="text-align:center">Nombre</th>
                <th style="text-align:center">Paterno</th>
                <th style="text-align:center">Materno</th>
                <th style="text-align:center">Nro Doc</th>
                <th style="text-align:center">Tipo Ddoc</th>
                <th style="text-align:center">Eliminar</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        function cargarFilaHuesped(persona_id,nombre,paterno,materno,nro_doc,tipo_doc){
            $('#tbl_detalle_huesped').append($('<tr>')
               .append($('<td>').append('<input type="hidden" name="vec_huesped_persona_id[]" value="'+persona_id+'">'+nombre))
               .append($('<td style="text-align:center">').append(paterno))
               .append($('<td style="text-align:center">').append(materno))
               .append($('<td style="text-align:center">').append(nro_doc))
               .append($('<td style="text-align:center">').append(tipo_doc))
               .append($('<td style="text-align:center">').append('<button type="button" class="btn btn-danger" onclick="eliminarFilaHuesped(this);">Eliminar</button>'))
            );
        }

        function eliminarFilaHuesped($this){
            fila=$($this).closest("tr");
            boot4.confirm({
                msg:"Quitar Huesped?",
                title:"Confirmación",
                callback:function(result){
                    if(result){
                        fila.remove();
                    }// fin if
                }
            });

        }//fin function

   </script>
@endpush
