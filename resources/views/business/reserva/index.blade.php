@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        @include('business/reserva/actionbar',['','titulo'=>'RESERVAS'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Fecha Registro</th>
                        <th>Cliente</th>
                        <th>Num Hab.</th>
                        <th>Tipo Habitacion</th>
                        <th>Paquete</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Final</th>
                        <th>Num Adulto</th>
                        <th>Num Niño</th>
                        <th>Pais Procedencia</th>
                        <th>Ciudad Procedencia</th>
                        <th>Detalle</th>
                        <th>Estado</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </thead>
                </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/reserva'])
        @include('business/reserva/create_edit')
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateReserva", function(){ //El boton btnCreateCliente se encuentra en actionbar
                $("#edit").val("");
                $("#title_modal_view_cliente").text("NUEVA RESERVA");
                limpiarDatoReserva();
                $('#modalViewReserva').modal('show');
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'fecha'},
                            {data:'cliente'},
                            {data:'num_habitacion'},
                            {data:'tipo_habitacion'},
                            {data:'paquete'},
                            {data:'fecha_ini'},
                            {data:'fecha_fin'},
                            {data:'num_adulto'},
                            {data:'num_nino'},
                            {data:'pais'},
                            {data:'ciudad'},
                            {data:'detalle'},
                            {data:'estado_reserva'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="dataEditReserva(this);">Editar</button></a>';
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
            datatable_datos=$('#tblListaDatos').DataTable({
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
