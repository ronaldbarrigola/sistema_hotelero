@extends('layouts.plantillaFormExtendido')
@section('contenido')
        @section('panelCabecera')
        @include('business/reporte/filterbarProduccion')
        <hr class="mb-0" size="10px" color="white" />  <!-- (mb-0) margin buton cero -->
        @include('business/reporte/actionbar',['titulo'=>'REPORTE PRODUCCION'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tbl_produccion" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Nro. Reserva</th>
                            <th>Fecha</th>
                            <th>Habitacion</th>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Tipo Transaccion</th>
                            <th>Ingreso</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="6">TOTALES</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endsection
@endsection

@push('scripts')
    <script>

        $(document).ready( function () {

            var columnas=[
                            {data:'reserva_id',className: "text-center"},
                            {data:'fecha'},
                            {data:'num_habitacion'},
                            {data:'producto'},
                            {data:'cliente'},
                            {data:'tipo_transaccion'},
                            {data:'monto',className: "text-center"},
                        ];

            datatable_datos=$('#tbl_produccion').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":false, //true para que cargue, cuando se cambie de pagina
                "order": [[ 0, "desc" ]],
                "ajax": {   "url" : "{{url('/business/reporte/produccion')}}",
                            "data" :function(d){
                                      d.habitacion_id=$("#habitacion_id").val();
                                      d.producto_id=$("#producto_id").val();
                                      d.fecha_ini=$("#fecha_ini").val();
                                      d.fecha_fin=$("#fecha_fin").val();
                                    },
                            "type" : "get"
                        },
                "columns":columnas,
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    //BEGIN:Calcular total ingreso
                    data = api.column( 6 ).data(); //Total paginas
                    var totalIngreso = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular huesped total

                    // Update footer

                    $( api.column( 6 ).footer() ).html(
                        '<span style="color:#00008B">'+ totalIngreso.toFixed(2) +'</span>'
                    );
                },
            });

            $("#btnFiltrar").on( "click", function() {
                datatable_datos.ajax.reload();//recargar registro datatables.
            });

        });//fin ready

    </script>
@endpush


