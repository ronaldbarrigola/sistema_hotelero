
<div class="row">
    <div class="col-12">
        <table id="tbl_detalle_huesped" class="table table-striped table-bordered table-condensed table-hover" style="width:100%">
            <thead>
                <th style="text-align:center">Nombre</th>
                <th style="text-align:center">Paterno</th>
                <th style="text-align:center">Materno</th>
                <th style="text-align:center">Nro. Doc.</th>
                <th style="text-align:center">Tipo Doc</th>
                <th style="text-align:center">Fecha Ingreso</th>
                <th style="text-align:center">Fecha Salida</th>
                <th style="text-align:center">Estado</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('business/huesped/create_edit')

@push('scripts')
    <script>

        var huesped_reserva_id="";
        $(document).ready( function () {

            $(document).on("click", "#btnCreateHuesped", function(){
                createHuesped();
            });

            obtenerPersonas();

            var columnas=[
                        {data:'nombre',
                            className: "text-center",
                            orderable:false,
                            render: function ( data, type, row ){
                                return '<input type="hidden" name="vec_huesped_id[]" value="'+row.id+'">';
                            }
                        },
                        {data:'paterno'},
                        {data:'materno'},
                        {data:'doc_id'},
                        {data:'tipo_documento'},
                        {data:'fecha_ingreso'},
                        {data:'fecha_salida'},
                        {data:'estado_huesped'}
                    ];

            datatable_huesped=$('#tbl_detalle_huesped').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 1, "desc" ]],
                "ajax": {   "url" : "{{url('/business/huesped')}}",
                            "data" :function(d){
                                      d.reserva_id=huesped_reserva_id; //text
                                    },
                            "type" : "get"
                        },
                "columns":columnas
            });
        });//fin ready

        function obtenerPersonas(){
            $("#huesped_persona_id").find('option').remove();
            $.ajax({
                type: "GET",
                url: "{{route('obtenerpersonas')}}",
                data:{'_token': '{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    $.each(result.personas,function(i, v) {
                        $("#huesped_persona_id").append('<option value="' + v.id + '">' + v.nombre_completo + '</option>');
                    });
                    $("#huesped_persona_id").selectpicker('refresh');
                },//End success
                complete:function(result, textStatus ){

                }
            });//End Ajax
        }

        function eliminarFilaHuesped(boton){
            fila=$(boton).closest("tr");//obtiene el primer padre que sea de tipo tr
            boot4.confirm({
                msg:"Quitar Huesped?",
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

   </script>
@endpush
