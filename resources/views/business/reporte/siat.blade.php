@extends('layouts.plantillaFormExtendido')
@section('contenido')
        @section('panelCabecera')
        @include('business/reporte/filterbarSiat')
        <hr class="mb-0" size="10px" color="white" />  <!-- (mb-0) margin buton cero -->
        @include('business/reporte/actionbar',['titulo'=>'REPORTE SIAT'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tbl_huespedes" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Huesped</th>
                            <th>Documento</th>
                            <th>Nacionalidad</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Salida</th>
                            <th>Nro. Factura</th>
                            <th>Nro Autorizacion</th>
                            <th>Observacion</th>
                            <th>NIT</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endsection
@endsection

{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        var  datatable_datos="";
        $(document).ready( function () {
            var columnas=[
                            {data:'huesped'},
                            {data:'doc_id'},
                            {data:'nacionalidad'},
                            {data:'fecha_ingreso'},
                            {data:'fecha_salida'},
                            {data:'nro_factura'},
                            {data:'nro_autorizacion'},
                            {data:'observacion'},
                            {data:'nit'}
                        ];

            datatable_datos=$('#tbl_huespedes').DataTable({
                "processing":true,
                "ordering": false, // Deshabilita la ordenación automática
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":false, //true para que cargue, cuando se cambie de pagina
                "ajax": {   "url" : "{{url('/business/reporte/siat')}}",
                            "data" :function(d){
                                      d.fecha_ini=$("#fecha_ini").val();
                                      d.fecha_fin=$("#fecha_fin").val();
                                    },
                            "type" : "get"
                        },
                "columns":columnas,
            });
        });//fin ready

    </script>
@endpush

