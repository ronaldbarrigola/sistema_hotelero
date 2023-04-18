
<div class="row">
    <div class="col">
        <table id="tbl_cargo" class="table table-striped table-bordered table-sm table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>Nro. Cargo</th>
                    <th>Transaccion</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Detalle</th>
                    <th>Cantidad</th>
                    <th>Precio Unidad</th>
                    <th>Total</th>
                    <th>Descuento%</th>
                    <th>Descuento</th>
                    <th>Cargo</th>
                    <th>Pago</th>
                    <th>Saldo</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
               </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
               </tr>
            </tfoot>
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
                            {data:'descuento_porcentaje'},
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
                "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    //BEGIN:Calcular cantidades
                    data = api.column( 5 ).data();// Total paginas
                    totalCantidad = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular cantidades

                    //BEGIN:Calcular precio precio unidad
                    data = api.column( 6 ).data();// Total paginas
                    precioUnidadTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;

                    //END:Calcular precio precio unidad

                    //BEGIN:Calcular Totales
                    data = api.column( 7 ).data(); //Total paginas
                    total = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;

                    //END:Calcular Totales

                    //BEGIN:Calcular Descuento porcentaje
                    data = api.column( 8 ).data(); //Total paginas
                    descuentoPorcentajeTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular Descuento

                    //BEGIN:Calcular Descuento
                    data = api.column( 9 ).data(); //Total paginas
                    descuentoTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular Descuento

                    //BEGIN:Calcular Cargo
                    data = api.column( 10 ).data(); //Total paginas
                    cargoTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;

                    //END:Calcular Cargo

                    //BEGIN:Calcular Pago
                    data = api.column( 11 ).data(); //Total paginas
                    pagoTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;

                    //END:Calcular Pago

                    //BEGIN:Calcular Saldo
                    data = api.column( 12 ).data(); //Total paginas
                    saldoTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;

                    //END:Calcular Saldo


                    // Update footer
                    $( api.column( 5 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ totalCantidad +'</span>'
                    );

                    $( api.column( 6 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(precioUnidadTotal).toFixed(2) +'</span>'
                    );

                    $( api.column( 7 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(total).toFixed(2) +'</span>'
                    );

                    $( api.column( 8 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(descuentoPorcentajeTotal).toFixed(2)  +'%</span>'
                    );

                    $( api.column( 9 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(descuentoTotal).toFixed(2)  +'</span>'
                    );

                    $( api.column( 10 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(cargoTotal).toFixed(2)  +'</span>'
                    );

                    $( api.column( 11 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(pagoTotal).toFixed(2)  +'</span>'
                    );

                    $( api.column( 12 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(saldoTotal).toFixed(2)  +'</span>'
                    );

                },
            });

        });//fin ready

    </script>
@endpush
