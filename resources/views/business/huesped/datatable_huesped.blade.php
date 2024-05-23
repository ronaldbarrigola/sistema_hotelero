<div class="row">
    <div class="col-12">
        <table id="tbl_huesped" class="table table-striped table-bordered table-sm table-hover" style="width:100%">
            <thead>
                <th style="text-align:center">Nombre</th>
                <th style="text-align:center">Paterno</th>
                <th style="text-align:center">Materno</th>
                <th style="text-align:center">Nro. Doc.</th>
                <th style="text-align:center">Tipo Doc</th>
                <th style="text-align:center">Fecha Ingreso</th>
                <th style="text-align:center">Fecha Salida</th>
                <th style="text-align:center">Estado</th>
                <th style="text-align:center">Check Out</th>
                <th style="text-align:center">Eliminar</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@include('business/huesped/create_edit')

@push('scripts')
    <script>

        var datatable_huesped="";

        $(document).ready( function () {

            $(document).on("click", "#btnCreateHuesped", function(){
                createHuesped();
            });

            var columnas=[
                        {data:'nombre',
                            orderable:false,
                            render: function ( data, type, row ){
                                return '<input type="hidden" name="vec_huesped_id[]" value="'+row.id+'">' + row.nombre;
                            }
                        },
                        {data:'paterno'},
                        {data:'materno'},
                        {data:'doc_id'},
                        {data:'tipo_documento'},
                        {data:'fecha_ingreso'},
                        {data:'fecha_salida'},
                        {data:'estado_huesped',className: "text-center"},
                        {data:'id',
                            className: "text-center",
                            orderable:false,
                            render: function ( data, type, row ){
                                if(row.estado_huesped_id==1){
                                    return '<button id="'+row.id+ '" class="btn btn-primary" onclick="huespedCheckOut(id);">Check Out</button>';
                                } else {
                                    return '<button id="'+row.id+ '" class="btn btn-secondary" disabled>Check Out</button>';
                                }
                            }
                        },
                        {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-danger" onclick="deleteHuesped(id);">Eliminar</button></a>';
                                }
                        }
                    ];

            datatable_huesped=$('#tbl_huesped').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[0, "desc" ]],
                "ajax": {   "url" : "{{url('/business/huesped')}}",
                            "data" :function(d){
                                      d.reserva_id=$("#huesped_reserva_id").val(); //text
                                    },
                            "type" : "get"
                        },
                "columns":columnas
            });
        });//fin ready

   </script>
@endpush
