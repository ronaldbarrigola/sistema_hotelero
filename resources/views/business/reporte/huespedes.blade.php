@extends('layouts.plantillaFormExtendido')
@section('contenido')
        @section('panelCabecera')
        @include('business/reporte/filterbarHuespedes')
        <hr class="mb-0" size="10px" color="white" />  <!-- (mb-0) margin buton cero -->
        @include('business/reporte/actionbar',['titulo'=>'REPORTE HUESPEDES'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tbl_huespedes" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Nro. Reserva</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Salida</th>
                            <th>Huesped</th>
                            <th>Nro. Hab</th>
                            <th>Pais</th>
                            <th>Ciudad</th>
                            <th>Profesion</th>
                            <th>Edad</th>
                            <th>Nro. Documento</th>
                            <th>Movimiento</th>
                            <th>Estado</th>
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
                            {data:'reserva_id'},
                            {data:'fecha_ingreso'},
                            {data:'fecha_salida'},
                            {data:'huesped'},
                            {data:'num_habitacion'},
                            {data:'pais'},
                            {data:'ciudad'},
                            {data:'profesion'},
                            {data:'edad'},
                            {data:'doc_id'},
                            {data:'movimiento'},
                            {data:'estado_huesped'},
                        ];

            datatable_datos=$('#tbl_huespedes').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":false, //true para que cargue, cuando se cambie de pagina
                "order": [[ 0, "desc" ]],
                "ajax": {   "url" : "{{url('/business/reporte/huespedes')}}",
                            "data" :function(d){
                                      d.habitacion_id=$("#habitacion_id").val();
                                      d.estado_huesped_id=$("#estado_huesped_id").val();
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

