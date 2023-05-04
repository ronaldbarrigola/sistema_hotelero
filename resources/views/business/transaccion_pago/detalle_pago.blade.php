<div class="row">
    <div class="col-12 col-md-12">
        <table id="tbl_detalle_transaccion_pago" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
                <th style="text-align:center">Cantidad</th>
                <th style="text-align:left">Producto</th>
                <th style="text-align:center">Precio Unidad</th>
                <th style="text-align:center">Descuento</th>
                <th style="text-align:center">Sub Total</th>
            </thead>
            <tbody>

            </tbody>
            <tfoot>
                <th style="text-align:right" colspan="4">TOTAL A PAGAR</th>
                <th style="text-align:center"><strong id="total_pago">0</strong></th>
            </tfoot>
        </table>
      </div>
</div>

@push('scripts')
    <script>
        function cargarFilaTransaccionPago(transaccion_pago_id,transaccion_id,cantidad,producto,precio_unidad,descuento,monto){
            $('#tbl_detalle_transaccion_pago').append($('<tr>')
                .append($('<td style="text-align:center">').append('<input type="hidden" name=p_cantidad[]" value="'+cantidad+'">' + cantidad))
                .append($('<td>').append('<input type="hidden" name="p_transaccion_pago_id[]" value="'+transaccion_pago_id+'"><input type="hidden" name="p_transaccion_id[]" value="'+transaccion_id+'">'+producto))
                .append($('<td style="text-align:center">').append('<input type="hidden" name="p_precio_unidad[]" value="'+precio_unidad+'">'+precio_unidad))
                .append($('<td style="text-align:center">').append('<input type="hidden" name="p_descuento[]" value="'+descuento+'">'+descuento))
                .append($('<td style="text-align:center">').append('<input type="hidden" name="p_monto[]" value="'+parseFloat(monto).toFixed(2)+'">'+parseFloat(monto).toFixed(2)))
            );
            transaccionPagoTotales();
        }

        function transaccionPagoTotales(){
            var total=0;
            $("input[name='p_monto[]']").each(function(indice, elemento) {
                var sub_total=$(elemento).val();
                if(sub_total!=""&&sub_total>0&&sub_total!=null){
                    total+=parseFloat(sub_total);
                }
            });

            $("#total_pago").html(Number.parseFloat(total).toFixed(2));
            $("#fp_monto_base").val(Number.parseFloat(total).toFixed(2)); //Modulo formapago
        }

   </script>
@endpush

