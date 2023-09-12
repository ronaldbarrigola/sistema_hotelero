
<div class="row">
    <div class="col">
        <table id="tbl_transaccion" class="table table-striped table-bordered table-sm table-hover" style="width:100%">
            <thead>
                <tr>
                    {{-- <th>Nro. Cargo</th> --}}
                    <th>Transaccion</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Detalle</th>
                    <th>Cantidad</th>
                    <th>Precio Unidad</th>
                    <th>Total</th>
                    <th>Anticipo</th>
                    <th>Descuento%</th>
                    <th>Descuento</th>
                    <th>Cargo</th>
                    <th>Pago</th>
                    <th>Saldo</th>
                    <th>Opcion</th>
                    <th>Anticipo</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
               </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>TOTAL</th>
                    {{-- <th></th> --}}
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
                    <th></th>
                    <th></th>
               </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="btn-toolbar d-flex justify-content-end" role="toolbar" aria-label="Toolbar with button groups">
    <div class="input-group input-group-inline">
        <div class="input-group-prepend">
            <div class="input-group-text">Bs.</div>
        </div>
        <input type="number" name="monto_pago" id="monto_pago" min="1" step="0.01" size="60" readonly class="form-control" placeholder="0">
        <div class="input-group-append">
            <button type="button" class="btn btn-primary" id="btnPagarTransaccion" onclick="sendTransaccionPago()">Pagar Cargo</button>
        </div>
    </div>
</div>

@include('business/transaccion/create_edit')
@include('business/transaccion_pago/create_edit')
@push('scripts')
    <script>
        var datatable_transaccion="";
        $(document).ready( function () {

            $(document).on("click", "#btnCreateTransaccion", function(){
                createTransaccion();
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id',className: "text-center"},
                            {data:'fecha'},
                            {data:'producto'},
                            {data:'detalle'},
                            {data:'cantidad',className: "text-center"},
                            {data:'precio_unidad'},
                            {data:'total'},
                            {data:'anticipo'},
                            {data:'descuento_porcentaje'},
                            {data:'descuento'},
                            {data:'cargo'},
                            {data:'pago'},
                            {data:'saldo'},
                            {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function ( data, type, row ){
                                    if(row.saldo > 0){
                                        return '<input type="hidden" name="tr_transaccion_id[]" value="'+row.id+'"><input type="hidden" name="tr_cantidad[]" value="'+row.cantidad+'"><input type="hidden" name="tr_precio_unidad[]" value="'+row.precio_unidad+'"><input type="hidden" name="tr_producto[]" value="'+row.producto+'"><input type="hidden" name="tr_descuento[]" value="'+row.descuento+'"><input type="hidden" name="tr_monto[]" value="'+row.saldo+'"><input type="checkbox" id="'+ data +'" onchange="selectCheckboxPago();" class="form-control" style="transform:scale(0.6);">';
                                    } else {
                                        return '<input type="hidden" name="tr_transaccion_id[]" value="0"><input type="hidden" name="tr_cantidad[]" value="0"><input type="hidden" name="tr_precio_unidad[]" value="0"><input type="hidden" name="tr_producto[]" value=""><input type="hidden" name="tr_descuento[]" value="0"><input type="hidden" name="tr_monto[]" value="0"><strong>PAGADO</strong>';
                                    }
                                }
                            },
                            {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function ( data, type, row ){
                                    if(row.saldo > 0){
                                        return '<button id="'+data+ '" class="btn btn-success" onclick="createTransaccionAnticipo(this);">Anticipo</button>';
                                    } else {
                                        return '<button id="'+data+ '" class="btn btn-success" disabled>Anticipo</button>';
                                    }
                                }
                            },
                            {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editTransaccion(id);">Editar</button>';
                                }
                            },
                            {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-danger" onclick="deleteTransaccion(id);">Eliminar</button>';
                                }
                            },
                        ];
            // ══════════════════════ CARGANDO DataTable por AJAX  ══════════════════════
            datatable_transaccion=$('#tbl_transaccion').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                //"iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "bPaginate": false,
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
                    data = api.column( 4 ).data();// Total paginas
                    totalCantidad = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular cantidades

                    //BEGIN:Calcular precio precio unidad
                    data = api.column( 5 ).data();// Total paginas
                    precioUnidadTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular precio precio unidad

                    //BEGIN:Calcular Totales
                    data = api.column( 6 ).data(); //Total paginas
                    total = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;

                    //END:Calcular Totales

                    //BEGIN:Calcular Anticipo
                    data = api.column( 7 ).data(); //Total paginas
                    anticipoTotal = data.length ?
                        data.reduce( function (a, b) {
                                return parseFloat(a) + parseFloat(b);
                        } ) :0;
                    //END:Calcular Anticipo

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
                    $( api.column( 4 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ totalCantidad +'</span>'
                    );

                    $( api.column( 5 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(precioUnidadTotal).toFixed(2) +'</span>'
                    );

                    $( api.column( 6 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(total).toFixed(2) +'</span>'
                    );

                    $( api.column( 7 ).footer() ).html(
                        '<br> <span style="color:#00008B">'+ parseFloat(anticipoTotal).toFixed(2)  +'</span>'
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
                "drawCallback": function( settings ) {
                    $("#monto_pago").val(0);
                },
            });

        });//fin ready

        function selectCheckboxPago(){
            var total=0;
            $("#monto_pago").val("");
            $("#tbl_transaccion input[type=checkbox]:checked").each(function () {
                var fila=$(this).closest("tr");
                var vec_monto=$(fila).find("input[name='tr_monto[]']");
                var input_monto=vec_monto[0];
                var monto=$(input_monto).val();
                total=total+parseFloat(monto);
            });
            $("#monto_pago").val(parseFloat(total).toFixed(2));
        }

        function sendTransaccionPago(){
            var monto_pago=$("#monto_pago").val();
            monto_pago=(monto_pago!=null&&monto_pago!=""&&monto_pago>0)?monto_pago:0;
            if(monto_pago<=0){
                messageAlert("Debe seleccionar opciones para pagar");
                return 0;
            }
            limpiarDatoTransaccionPago();//Se ecuentra en el modulo transaccion_pago
            limpiarFormaPago();//Se ecuentra en el modulo formapago
            $("#tbl_transaccion input[type=checkbox]:checked").each(function () {
                var fila=$(this).closest("tr");

                //Transaccion Id
                var vec_transaccion_id=$(fila).find("input[name='tr_transaccion_id[]']");
                var input_transaccion_id=vec_transaccion_id[0];
                var transaccion_id=$(input_transaccion_id).val();

                //Cantidad
                var vec_cantidad=$(fila).find("input[name='tr_cantidad[]']");
                var input_cantidad=vec_cantidad[0];
                var cantidad=$(input_cantidad).val();
                cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;

                //Precio Unidad
                var vec_precio_unidad=$(fila).find("input[name='tr_precio_unidad[]']");
                var input_precio_unidad=vec_precio_unidad[0];
                var precio_unidad=$(input_precio_unidad).val();
                precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;

                //Producto
                var vec_producto=$(fila).find("input[name='tr_producto[]']");
                var input_producto=vec_producto[0];
                var producto=$(input_producto).val();

                //Descuento
                var vec_descuento=$(fila).find("input[name='tr_descuento[]']");
                var input_descuento=vec_descuento[0];
                var descuento=$(input_descuento).val();
                descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;

               //Monto
                var vec_monto=$(fila).find("input[name='tr_monto[]']");
                var input_monto=vec_monto[0];
                var monto=$(input_monto).val();
                monto=(monto!=null&&monto!=""&&monto>0)?monto:0;
                cargarFilaTransaccionPago(0,transaccion_id,cantidad,producto,precio_unidad,descuento,monto);
            });
            createTransaccionPago();
        }



    </script>
@endpush
