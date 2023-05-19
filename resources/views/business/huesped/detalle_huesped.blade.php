<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-12">
        <div class="form-group">
            <label for="huesped_cliente_id" class="my-0" ><strong>Huesped:</strong></label>
            <div class="input-group-append">
                <select id="huesped_cliente_id" name="huesped_cliente_id" class="form-control selectpicker" data-live-search="true">
                    {{--Datos cargados mediante ajax--}}
                </select>
                <button type="button" id="btnNuevoHuespedCliente" class="input-group-btn btn btn-light"><span class="icon-plus"></span></button>
            </div>
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

        $(document).ready(function() {
            $(document).on("change", "#huesped_cliente_id", function(){
                addHuesped();
            });
            obtenerClientes();
        });//Fin ready

        function cargarFilaHuesped(cliente_id,nombre,paterno,materno,nro_doc,tipo_doc){
            $('#tbl_detalle_huesped').append($('<tr>')
               .append($('<td>').append('<input type="hidden" name="vec_huesped_cliente_id[]" value="'+cliente_id+'">'+nombre))
               .append($('<td>').append(paterno))
               .append($('<td>').append(materno))
               .append($('<td>').append(nro_doc))
               .append($('<td>').append(tipo_doc))
               .append($('<td style="text-align:center">').append('<button type="button" class="btn btn-danger" onclick="eliminarFilaHuesped(this);">Eliminar</button>'))
            );
            huespedValidateSave();
        }

        function addHuesped(){
            var cliente_id=$("#huesped_cliente_id").val();
            var nombre=$('#huesped_cliente_id option:selected').data("nombre");
            var paterno=$('#huesped_cliente_id option:selected').data("paterno");
            var materno=$('#huesped_cliente_id option:selected').data("materno");
            var nro_doc=$('#huesped_cliente_id option:selected').data("nro_doc");
            var tipo_doc=$('#huesped_cliente_id option:selected').data("tipo_doc");

            //Validaciones
            nombre=(nombre!=null)?nombre:"";
            paterno=(paterno!=null)?paterno:"";
            materno=(materno!=null)?materno:"";
            nro_doc=(nro_doc!=null)?nro_doc:"";
            tipo_doc=(tipo_doc!=null)?tipo_doc:"";

            var registrado=false;
            $("input[name='vec_huesped_cliente_id[]']").each(function(indice, elemento) {
                if($(elemento).val()==cliente_id){
                    var fila=$(elemento).closest("tr");
                    registrado=true;
                }
            });

            $("#huesped_cliente_id").selectpicker("val","");
            $("#huesped_cliente_id").selectpicker('refresh');

            if(!registrado) {
                cargarFilaHuesped(cliente_id,nombre,paterno,materno,nro_doc,tipo_doc)
            }
        }

        function obtenerClientes(){
            $("#huesped_cliente_id").find('option').remove();
            $.ajax({
                type: "GET",
                url: "{{route('obtenerclientes')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $.each(result.clientes,function(i, v) {
                        $("#huesped_cliente_id").append('<option value="' + v.id + '" data-nombre="' + v.nombre + '" data-paterno="' + v.paterno + '" data-materno="' + v.materno + '" data-nro_doc="' + v.doc_id + '" data-tipo_doc="' + v.tipo_documento + '">' + v.cliente + " | " + v.doc_id + '</option>');
                    });
                    $("#huesped_cliente_id").selectpicker('refresh');
                },//End success
                complete:function(result, textStatus ){

                }
            });//End Ajax
        }

        function eliminarFilaHuesped($this){
            fila=$($this).closest("tr");
            boot4.confirm({
                msg:"Quitar Huesped?",
                title:"Confirmación",
                callback:function(result){
                    if(result){
                        fila.remove();
                        huespedValidateSave();
                    }// fin if
                }
            });

        }//fin function

        function huespedValidateSave(){
            if($("#tbl_detalle_huesped>tbody>tr:visible").length>0){
               $("#btnGuardarHuesped").removeAttr("disabled");
            }else {
               $("#btnGuardarHuesped").attr("disabled","disabled");
            }
        }

   </script>
@endpush
