
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-8 col-12">
        <div class="form-group">
            <label for="hotel_producto_id" class="my-0" ><strong>Producto:</strong></label>
            <select id="hotel_producto_id" name="hotel_producto_id" class="form-control selectpicker" data-live-search="true">
                 <!--Llenado de campos por ajax-->
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table id="tbl_detalle_transaccion" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th>Producto</th>
                    <th style="text-align:center">Cantidad</th>
                    <th style="text-align:center">Precio Unidad</th>
                    <th style="text-align:center">Descuento %</th>
                    <th style="text-align:center">Descuento Bs.</th>
                    <th style="text-align:center">Sub Total</th>
                    <th style="text-align:center">Opcion</th>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <th>TOTALES</th>
                    <th style="text-align:center"><strong id="cantidad_total">0</strong></th>
                    <th style="text-align:center"><strong id="precio_unidad_total">0</strong></th>
                    <th style="text-align:center"><strong id="descuento_porcentaje_total">0</strong></th>
                    <th style="text-align:center"><strong id="descuento_total">0</strong></th>
                    <th style="text-align:center"><strong id="total">0</strong></th>
                    <th style="text-align:center"></th>
                </tfoot>
            </table>
        </div>
      </div>
</div>

@push('scripts')
    <script>
        function cargarFilaTransaccion(transaccion_id,hotel_producto_id,producto,cantidad,precio_unidad,descuento_porcentaje,descuento,sub_total,estado){
            sub_total=cantidad*precio_unidad;

            var input_precio_unidad='<input type="number" name="vec_precio_unidad[]" class="form-control" onkeydown="event.preventDefault()" required min="1" step="0.01" value="'+precio_unidad+'" style="text-align:center;background-color:#f6f6f6;" placeholder="0">';
            if(precio_unidad==""||precio_unidad==0||precio_unidad==null){
                input_precio_unidad='<input type="number" name="vec_precio_unidad[]" class="form-control" required min="1" step="0.01" onkeyup="transaccionSubTotal(this)" style="text-align:center;" placeholder="0">';
            }

            $('#tbl_detalle_transaccion').append($('<tr>')
               .append($('<td>').append('<input type="hidden" name="vec_transaccion_id[]" value="'+transaccion_id+'"><input type="hidden" name="vec_hotel_producto_id[]" value="'+hotel_producto_id+'">'+producto))
               .append($('<td style="text-align:center">').append('<input type="number" name="vec_cantidad[]" class="form-control" required min="1" value="'+cantidad+'" onkeyup="transaccionSubTotal(this)" style="text-align:center" placeholder="0">'))
               .append($('<td style="text-align:center">').append(input_precio_unidad))
               .append($('<td style="text-align:center">').append('<input type="number" name="vec_descuento_porcentaje[]" class="form-control" step="0.01" value="'+descuento_porcentaje+'" onkeyup="transaccionDescuentoPorcentaje(this)" style="text-align:center" placeholder="0">'))
               .append($('<td style="text-align:center">').append('<input type="number" name="vec_descuento[]" class="form-control" step="0.01" value="'+descuento+'" onkeyup="transaccionDescuento(this)" style="text-align:center" placeholder="0">'))
               .append($('<td style="text-align:center">').append('<input type="number" name="vec_monto[]" onkeydown="event.preventDefault()" required min="1" step="0.01" class="form-control" value="'+ sub_total.toFixed(2) +'" style="text-align:center;background-color:#f6f6f6;">'))
               .append($('<td style="text-align:center">').append('<input type="hidden" name="vec_estado[]" value="'+estado+'"><button type="button" class="btn btn-danger" onclick="eliminarFilaTransaccion(this);">Eliminar</button>'))
            );

            transaccionSubTotal();
            validateSave();
        }

        function transaccionDescuentoPorcentaje($this){
            fila=$($this).closest("tr");
            var sub_total=0;
            var porcentaje=$("#descuento_porcentaje").val();
            var descuento=0;

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

            //valores descuento porcentaje
            var vec_descuento_porcentaje=$(fila).find("input[name='vec_descuento_porcentaje[]']");
            var input_descuento_porcentaje=vec_descuento_porcentaje[0];
            var porcentaje=$(input_descuento_porcentaje).val();
            porcentaje=(porcentaje!=null&&porcentaje!=""&&porcentaje>0)?porcentaje:0;

            //valores descuento
            var vec_descuento=$(fila).find("input[name='vec_descuento[]']");
            var input_descuento=vec_descuento[0];


            sub_total=cantidad*precio_unidad;

            if(porcentaje>0&&porcentaje<=100){
                //descuento=Math.round(parseFloat((sub_total*porcentaje)/100));
                descuento=Math.round(parseFloat((sub_total*porcentaje)/100)*100.0)/100.0;
            } else if(porcentaje>100) {
                $(input_descuento_porcentaje).val(100);
                descuento=sub_total;
            } else {
                $(input_descuento_porcentaje).val("");
            }
            $(input_descuento).val(descuento);

            transaccionSubTotal($this)

        }

        function transaccionDescuento($this){
            fila=$($this).closest("tr");
            var sub_total=0;
            var porcentaje=0;
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

            //valores descuento porcentaje
            var vec_descuento_porcentaje=$(fila).find("input[name='vec_descuento_porcentaje[]']");
            var input_descuento_porcentaje=vec_descuento_porcentaje[0];

            //valores descuento
            var vec_descuento=$(fila).find("input[name='vec_descuento[]']");
            var input_descuento=vec_descuento[0];
            var descuento=$(input_descuento).val();
            descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;

            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
            sub_total=cantidad*precio_unidad;
            descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
            if(descuento>0&&descuento<=sub_total){
                //porcentaje=Math.round(parseFloat((descuento/sub_total)*100));
                porcentaje=Math.round(parseFloat((descuento/sub_total)*100)*100.0)/100.0;
            } else if(descuento>sub_total) {
                $(input_descuento).val(sub_total);
                porcentaje=100;
            } else {
                $(input_descuento).val("");
            }
            $(input_descuento_porcentaje).val(porcentaje);
            transaccionSubTotal($this)
        }

        function transaccionSubTotal($this){
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
            var vec_monto=$(fila).find("input[name='vec_monto[]']");
            var input_monto=vec_monto[0];
            $(input_monto).val(sub_total);

            transaccionTotales();

        }

        function transaccionTotales(){
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


            $("input[name='vec_monto[]']").each(function(indice, elemento) {
                var sub_total=$(elemento).val();
                if(sub_total!=""&&sub_total>0&&sub_total!=null){
                    total+=parseFloat(sub_total);
                }
            });

            $("#cantidad_total").html(Number.parseFloat(cantidadTotal));
            $("#precio_unidad_total").html(Number.parseFloat(precioUnidadTotal).toFixed(2));
            $("#descuento_porcentaje_total").html(Number.parseFloat(descuentoPorcentajeTotal).toFixed(2));
            $("#descuento_total").html(Number.parseFloat(descuentoTotal).toFixed(2));
            $("#total").html(Number.parseFloat(total).toFixed(2));
        }

        function eliminarFilaTransaccion(boton){
            fila=$(boton).closest("tr");//obtiene el primer padre que sea de tipo tr
            boot4.confirm({
                msg:"Quitar Producto?",
                title:"Confirmación",
                callback:function(result){
                    if(result){
                        vec_estado=$(fila).find("input[name='vec_estado[]']");//busca en todos sus hijos el input que tenga el nombre especificado
                        input_estado=$(vec_estado[0]); //como la busqueda se realizo dentro de la fila, el vector que agarró solo tiene el elemento de la fila, por eso en el indice se pone 0
                        if($(input_estado).val() === 'guardado'){
                            $(input_estado).val('eliminado');
                            fila.hide();
                        }if($(input_estado).val() === 'nuevo'){
                            fila.remove();
                        }

                        transaccionTotales();
                        validateSave();

                    }// fin if
                }
            });

        }//fin function

        function validateSave(){
            if($('#tbl_detalle_transaccion>tbody>tr:visible').length > 0){
                $("#btnGuardarTransaccion").removeAttr("disabled");
            }
            else {
                $("#btnGuardarTransaccion").attr("disabled","disabled");
            }
        }

   </script>
@endpush
