
<div class="row">
    <div class="col">
        <table id="tbl_cargo" class="table table-striped table-bordered table-sm table-hover" style="width:100%">
            <thead>
                <th>Nro. Cargo</th>
                <th>Transaccion</th>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Detalle</th>
                <th>Cantidad</th>
                <th>Precio Unidad</th>
                <th>Total</th>
                <th>Descuento</th>
                <th>Cargo</th>
                <th>Pago</th>
                <th>Saldo</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </thead>
        </table>
    </div>
</div>
@include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/transaccion'])
@include('business/transaccion/create_edit')

@push('scripts')
    <script>
        var datatable_transaccion="";
        $(document).ready( function () {

            $(document).on("click", "#btnCreateTransaccion", function(){
                createTransaccion();
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[  {data:'cargo_id'},
                            {data:'id'},
                            {data:'fecha'},
                            {data:'producto'},
                            {data:'detalle'},
                            {data:'cantidad'},
                            {data:'precio_unidad'},
                            {data:'total'},
                            {data:'descuento'},
                            {data:'cargo'},
                            {data:'pago'},
                            {data:'saldo'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editTransaccion(id);">Editar</button></a>';
                                }
                            },
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href='' class='preguntaeliminar btn btn-danger' data-target='#modaleliminar' data-toggle='modal' data-idmodelo='"+data+"'>Eliminar</a>";
                                }
                            },
                        ];
            // ══════════════════════ CARGANDO DataTable por AJAX  ══════════════════════
            datatable_transaccion=$('#tbl_cargo').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax": {   "url" : "{{url('/business/transaccion/')}}",
                            "data" :function(d){
                                      d.reserva_id=$("#foreign_reserva_id").val(); //text
                                    },

                            "type" : "get"
                        },
                "columns":columnas,
            });

        });//fin ready

    </script>
@endpush
