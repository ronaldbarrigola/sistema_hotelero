@extends('layouts.plantillaFormExtendido')
@section('contenido')
        @section('panelCabecera')
        @include('business/reporte/filterbarReservas')
        <hr class="mb-0" size="10px" color="white" />  <!-- (mb-0) margin buton cero -->
        @include('business/reporte/actionbar',['titulo'=>'REPORTE RESERVAS'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tbl_reservas" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Nro. Reserva</th>
                            <th>Fecha Registro</th>
                            <th>Cliente</th>
                            <th>Num Hab.</th>
                            <th>Tipo Habitacion</th>
                            <th>Servicio</th>
                            <th>Fecha Ingreso</th>
                            <th>Fecha Salida</th>
                            <th>Pais Procedencia</th>
                            <th>Ciudad Procedencia</th>
                            <th>Huesped Check In</th>
                            <th>Huesped Check Out</th>
                            <th>Total Huespedes</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="10">TOTALES</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endsection
@endsection

{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>

        $(document).ready( function () {
            var columnas=[
                            {data:'id'},
                            {data:'fecha'},
                            {data:'cliente'},
                            {data:'num_habitacion'},
                            {data:'tipo_habitacion'},
                            {data:'servicio'},
                            {data:'fecha_ini'},
                            {data:'fecha_fin'},
                            {data:'pais'},
                            {data:'ciudad'},
                            {data:'huesped_checkin',className: "text-center"},
                            {data:'huesped_checkout',className: "text-center"},
                            {data:'huesped_total',className: "text-center"},
                            {data:'estado_reserva'},
                        ];

            datatable_datos=$('#tbl_reservas').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":false, //true para que cargue, cuando se cambie de pagina
                "order": [[ 0, "desc" ]],
                "ajax": {   "url" : "{{url('/business/reporte/reservas')}}",
                            "data" :function(d){
                                      d.habitacion_id=$("#habitacion_id").val();
                                      d.tipo_habitacion_id=$("#tipo_habitacion_id").val();
                                      d.cliente_id=$("#cliente_id").val();
                                      d.estado_reserva_id=$("#estado_reserva_id").val();
                                      d.fecha_ini=$("#fecha_ini").val();
                                      d.fecha_fin=$("#fecha_fin").val();
                                    },
                            "type" : "get"
                        },
                "columns":columnas,
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    //BEGIN:Calcular huesped checkin
                    data = api.column( 10 ).data();// Total paginas
                    totalCheckIn = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //NED:Calcular huesped checkin

                    //BEGIN:Calcular huesped checkout
                    data = api.column( 11 ).data();// Total paginas
                    totalCheckOut = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //NED:Calcular huesped checkout

                    //BEGIN:Calcular huesped total
                    data = api.column( 12 ).data(); //Total paginas
                    totalHuesped = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular huesped total

                    // Update footer
                    $( api.column( 10 ).footer() ).html(
                        '<span style="color:#00008B">'+ totalCheckIn +'</span>'
                    );

                    $( api.column( 11 ).footer() ).html(
                        '<span style="color:#00008B">'+ totalCheckOut +'</span>'
                    );

                    $( api.column( 12 ).footer() ).html(
                        '<span style="color:#00008B">'+ totalHuesped +'</span>'
                    );
                },
            });

            //BEGIN: Calcular numero de filas a mostrar en pantalla
                // $(window).on('resize', function () {
                //     var newDisplayLength = calcularFilasAMostrar();
                //     datatable_datos.page.len(newDisplayLength).draw();
                // });

                // function calcularFilasAMostrar() {
                //     var screenWidth = $(window).width();
                //     if (screenWidth < 768) {
                //         return 5; // Por ejemplo, muestra 5 filas en pantallas pequeñas
                //     } else if (screenWidth < 1200) {
                //         return 10; // Muestra 10 filas en pantallas medianas
                //     } else {
                //         return 15; // Muestra 15 filas en pantallas grandes
                //     }
                // }
            //END: Calcular numero de filas a mostrar en pantalla

            $("#btnFiltrar").on( "click", function() {
                datatable_datos.ajax.reload();//recargar registro datatables.
            });

        });//fin ready


    </script>
@endpush

