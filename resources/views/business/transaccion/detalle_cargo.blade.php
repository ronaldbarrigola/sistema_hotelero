<div class="row">
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table id="tbl_detalle" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Producto</th>
                    <th>Fecha</th>
                    <th style="text-align:center">Cantidad</th>
                    <th style="text-align:center">Precio Unidad</th>
                    <th style="text-align:center">Descuento %</th>
                    <th style="text-align:center">Descuento Bs.</th>
                    <th style="text-align:center">Sub Total</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <th colspan="2">TOTAL</th>
                    <th style="text-align:center"><strong id="cantidad_total">0</strong></th>
                    <th style="text-align:center"><strong id="precio_unidad_total">0</strong></th>
                    <th style="text-align:center"><strong id="descuento_porcentaje_total">0</strong></th>
                    <th style="text-align:center"><strong id="descuento_total">0</strong></th>
                    <th style="text-align:center"><strong id="total">0</strong></th>
                </tfoot>
            </table>
        </div>
      </div>
</div>

@push('scripts')
    <script>
        function cargarFila(hotel_producto_id,producto,fecha,cantidad,precio_unidad,descuento_porcentaje,descuento,sub_total){
            sub_total=cantidad*precio_compra;
            $('#tbl_detalle').append($('<tr>')
               .append($('<td>').append('<input type="hidden" name="vec_hotel_producto_id[]" value="'+hotel_producto_id+'">'+producto))
               .append($('<td>').append('<input type="date" name="vec_fecha_ini[]" class="form-control" value="'+fecha+'">'))
               .append($('<td>').append('<input type="text" name="vec_cantidad[]" readonly value="'+cantidad+'">'))
               .append($('<td style="text-align:center">').append('<input type="text" name="vec_precio_unidad[]" class="form-control" required value="'+precio_unidad+'" style="text-align:center" placeholder="0">'))
               .append($('<td style="text-align:center">').append('<input type="text" name="vec_descuento_porcentaje[]" class="form-control" value="'+descuento_porcentaje+'" style="text-align:center" placeholder="0">'))
               .append($('<td style="text-align:center">').append('<input type="text" name="vec_descuento[]" class="form-control" required value="'+descuento+'" style="text-align:center" placeholder="0">'))
               .append($('<td style="text-align:center">').append('<input type="text" name="vec_sub_total[]" readonly class="form-control" value="'+ sub_total.toFixed(2) +'" style="text-align:center">'))
            );

            calcularSubTotal();

        }

        function calcularSubTotal($this){
            fila=$($this).closest("tr");

            //valores cantidad
            var vec_cantidad=$(fila).find("input[name='vec_cantidad[]']");
            var input_cantidad=vec_cantidad[0];
            var cantidad=$(input_cantidad).val();
            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;

            //valores precio unidad
            var vec_precio_unidad=$(fila).find("input[name='vec_precio_unidad[]']");
            var input_precio_unidad=vec_precio_unidad[0];
            var precio_unidad=$(input_precio_unidad).val();
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;

            //valores descuento
            var vec_descuento=$(fila).find("input[name='vec_descuento[]']");
            var input_descuento=vec_descuento[0];
            var descuento=$(input_descuento).val();
            descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;

            //Valores sub total
            var sub_total=cantidad*precio_unidad-descuento;
            var vec_sub_total=$(fila).find("input[name='vec_sub_total[]']");
            var input_sub_total=vec_sub_total[0];
            $(input_sub_total).val(sub_total);

            calcularTotales();

        }

        function calcularTotales(){
            var cantidadTotal=0;
            var precioUnidadTotal=0;
            var descuentoPorcentajeTotal=0;
            var descuentoTotal=0;
            var total=0;

            $("input[name='vec_cantidad[]']").each(function(indice, elemento) {
                var cantidad=$(elemento).val();
                if(cantidad!=""&&cantidad>0&&cantidad!=null){
                    cantidadTotal+=parseInt(cantidad);
                }
            });

            $("input[name='vec_precio_unidad[]']").each(function(indice, elemento) {
                var precioUnidad=$(elemento).val();
                if(precioUnidad!=""&&precioUnidad!=null&&precioUnidad>0){
                    precioUnidadTotal+=parseFloat(precioUnidad);
                }
            });

            $("input[name='vec_descuento_porcentaje[]']").each(function(indice, elemento) {
                var descuentoPorcentaje=$(elemento).val();
                if(descuentoPorcentaje!=""&&descuentoPorcentaje>0&&descuentoPorcentaje!=null){
                    descuentoPorcentajeTotal+=parseFloat(descuentoPorcentaje);
                }
            });

            $("input[name='vec_descuento[]']").each(function(indice, elemento) {
                var descuento=$(elemento).val();
                if(descuento!=""&&descuento>0&&descuento!=null){
                    descuentoTotal+=parseFloat(descuento);
                }
            });


            $("input[name='vec_sub_total[]']").each(function(indice, elemento) {
                var sub_total=$(elemento).val();
                if(sub_total!=""&&sub_total>0&&sub_total!=null){
                    total+=parseFloat(sub_total);
                }
            });

            $("#precio_unidad_total").html(Number.parseFloat(cantidadTotal).toFixed(2));
            $("#precio_unidad_total").html(Number.parseFloat(precioUnidadTotal).toFixed(2));
            $("#descuento_porcentaje_total").html(Number.parseFloat(descuentoPorcentajeTotal).toFixed(2));
            $("#descuento_total").html(Number.parseFloat(descuentoTotal).toFixed(2));
            $("#total").html(Number.parseFloat(total).toFixed(2));


        }

   </script>
@endpush
