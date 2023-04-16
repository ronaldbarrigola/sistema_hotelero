@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        @include('business/cargo/actionbar',['','titulo'=>'CARGOS'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
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
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/reserva'])
        @include('business/cargo/create_edit')
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>

        $(document).ready( function () {

            $(document).on("click", "#btnCreateCargo", function(){
                createCargo();
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
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editCargo(id);">Editar</button></a>';
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
                "ajax":"{{url('business/cargo')}}",
                "columns":columnas,
            });

        });//fin ready


    </script>
@endpush
