<div class="row">
    <div class="col">
        <table id="tbl_reserva" class="table table-striped table-bordered table-sm table-hover" style="width:100%">
            <thead>
                <th>Comp.</th>
                <th>Nro. Reserva</th>
                <th>Fecha Registro</th>
                <th>Cliente</th>
                <th>Num Hab.</th>
                <th>Tipo Habitacion</th>
                <th>Servicio</th>
                <th>Canal Reserva</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Salida</th>
                <th>Pais Procedencia</th>
                <th>Ciudad Procedencia</th>
                <th>Detalle</th>
                <th>Estado</th>
                <th>Cargos</th>
                <th>Huespedes</th>
                <th>Modificar</th>
                <th>Eliminar</th>
            </thead>
        </table>
    </div>
</div>
@include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/reserva'])
@include('business/cliente/create_edit')
@include('business/reserva/create_edit')

@push('scripts')
    <script>
        var datatable_reserva="";
        $(document).ready( function () {
            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+'" class="btn btn-danger" onclick="comprobanteReserva(id);">Pdf</button>';
                                },
                                className: "text-center"
                            },
                            {data:'id'},
                            {data:'fecha'},
                            {data:'cliente'},
                            {data:'num_habitacion'},
                            {data:'tipo_habitacion'},
                            {data:'servicio'},
                            {data:'canal_reserva'},
                            {data:'fecha_ini'},
                            {data:'fecha_fin'},
                            {data:'pais'},
                            {data:'ciudad'},
                            {data:'detalle'},
                            {data:'estado_reserva'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-primary" onclick="slideTransaccion(id);">Cargos</button>'; //slideTransaccion(id) se encuentra en el modulo transaccion.crete_edit
                                }
                            },
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-success" onclick="slideHuesped(this);">Huesped</button>';
                                }
                            },
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editReserva(id);">Editar</button>';
                                }
                            },

                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-danger" onclick="deleteReserva(id);">Eliminar</button></a>';
                                }
                            },

                        ];
            // ══════════════════════ CARGANDO DataTable por AJAX  ══════════════════════
            datatable_reserva=$('#tbl_reserva').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('business/reserva')}}",
                "columns":columnas,
            });

        });//fin ready

    </script>
@endpush
