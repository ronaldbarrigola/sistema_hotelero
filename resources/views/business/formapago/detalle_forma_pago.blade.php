<input type="hidden" id="fp_monto_base" value="0">
<div class="card">
    <div class="card-header py-0">
        <strong>DATOS FORMAS DE PAGO</strong>
    </div>
    <div class="card-body py-0">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="forma_pago_id" class="my-0" ><strong>Forma de Pago:</strong></label>
                    <div class="input-group-append">
                        <select name="forma_pago_id" id="forma_pago_id" required class="form-control selectpicker border" data-live-search="true" >
                           <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>
            </div>

            <div class="panel_concepto col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="concepto" class="my-0" ><strong>Concepto:</strong></label>
                    <input type="text" name="concepto"  id="concepto" maxlength="100" class="form-control">
                </div>
            </div>

        </div>
    </div>
</div>

<div class="panel_forma_pago row" style="display: none">
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table id="tbl_forma_pago" class="table table table-striped table-bordered table-condensed table-hover">
                <thead><tr>
                      <th style="text-align:center;vertical-align: middle">Pago Multiple</th>
                      <th style="text-align:center;vertical-align: middle">Concepto</th>
                      <th style="text-align:center;vertical-align: middle">Monto</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th style="text-align:center"><strong id="fp_total">0</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <input type="hidden" id="fp_saldo" value="0">

</div>

@push('scripts')
    <script>

        $(document).ready(function() {

            $(document).on("change", "#forma_pago_id", function(){
               var forma_pago_id=$("#forma_pago_id").val();
               if(forma_pago_id=="PM"){ //Pago Multiple
                  $(".panel_forma_pago").show();
                  $(".panel_concepto").hide();
               } else {
                  $(".panel_forma_pago").hide();
                  $(".panel_concepto").show();
               }
            });

        });//Fin ready

        function cargarFilaFormaPago(importe_id,forma_pago_id,forma_pago,concepto,monto){
            $('#tbl_forma_pago').append( $('<tr>')
               .append($('<td>').append('<input type="hidden" name="fp_importe_id[]" value="'+importe_id+'"><input type="hidden" name="fp_forma_pago_id[]" value="'+forma_pago_id+'">'+forma_pago))
               .append($('<td>').append('<input type="text" class="form-control" maxlength="100" name="fp_concepto[]" value="'+concepto+'">'))
               .append($('<td>').append('<input type="number" class="form-control" name="fp_monto[]" value="'+ monto +'" step="0.01" onkeyup="calcularFormaPagoTotales()" style="text-align:center" placeholder="0">'))
            );
            calcularFormaPagoTotales();
        }

        function calcularFormaPagoTotales(){
            var forma_pago_total=0;
            var monto=0;
            $("input[name='fp_monto[]']").each(function(indice, elemento) {
                monto=$(elemento).val();
                if(monto!=""&&monto>0&&monto!=null){
                    forma_pago_total+=parseFloat(monto);
                }
            });

            total=$("#fp_monto_base").val();
            total=(total!=null&&total!=""&&total>0)?total:0;
            total=parseFloat(total).toFixed(2)

            forma_pago_total=parseFloat(forma_pago_total).toFixed(2);
            saldo=total-forma_pago_total;

            $("#fp_total").html(parseFloat(forma_pago_total).toFixed(2));
            $("#fp_saldo").val(parseFloat(saldo).toFixed(2));
        }

        function limpiarFormaPago(){
           $(".panel_forma_pago").hide();
           $("#tbl_forma_pago tbody tr").find('td').remove();
           $("#fp_monto_base").val(0);
           $("#fp_total").text(0);
           $("#fp_saldo").val(0);
        }

   </script>
@endpush


