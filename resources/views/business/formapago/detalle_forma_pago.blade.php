<div class="panel_ordenante card">
    <div class="card-header py-0">
        <strong>FORMAS DE PAGO</strong>
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
        </div>
    </div>
</div>

<div class="panel_forma_pago row" style="display: none">
    <div class="col-12 col-md-12">
        <div class="table-responsive">
            <table id="tbl_forma_pago" class="table table table-striped table-bordered table-condensed table-hover">
                <thead><tr>
                      <th style="text-align:center;vertical-align: middle">Pago Multiple</th>
                      <th style="text-align:center;vertical-align: middle">Monto</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr><th>TOTAL</th>
                        <th style="text-align:center"><strong id="forma_pago_total">0</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col d-flex justify-content-end">
        <label><strong>Saldo :</strong></label>
        <input type="text" id="saldo" name="saldo" style="text-align:center;border:0;color:red" size="10" readonly value="0">
    </div>
</div>

@push('scripts')
    <script>

        $(document).ready(function() {

            $(document).on("change", "#forma_pago_id", function(){
               var forma_pago_id=$("#forma_pago_id").val();
               if(forma_pago_id=="PM"){ //Pago Multiple
                  $(".panel_forma_pago").show();
               } else {
                  $(".panel_forma_pago").hide();
               }
            });

        });//Fin ready

        function cargarFilaFormaPago(forma_pago_id,forma_pago,monto){
            $('#tbl_forma_pago').append( $('<tr>')
               .append($('<td>').append('<input type="hidden" name="vec_forma_pago_id[]" value="'+forma_pago_id+'">'+forma_pago))
               .append($('<td>').append('<input type="number" class="form-control" name="vec_monto[]" value="'+ monto +'" step="0.01" onkeyup="calcularFormaPagoTotales()" style="text-align:center" placeholder="0">'))
            );
            calcularFormaPagoTotales();
        }

        function calcularFormaPagoTotales(){
            var forma_pago_total=0;
            var monto=0;
            $("input[name='vec_monto[]']").each(function(indice, elemento) {
                monto=$(elemento).val();
                if(monto!=""&&monto>0&&monto!=null){
                    forma_pago_total+=parseFloat(monto);
                }
            });

            total=$("#total_pago").text();
            total_redondeado=parseFloat(total).toFixed(2)
            forma_pago_total_redondeado=parseFloat(forma_pago_total).toFixed(2);
            saldo=total_redondeado-forma_pago_total_redondeado;
            $("#forma_pago_total").html(parseFloat(forma_pago_total_redondeado).toFixed(2));
            $("#saldo").val(parseFloat(saldo).toFixed(2));
        }

        function limpiarFormaPago(){
           $("input[name='vec_monto[]']").each(function(indice, elemento) {
              $(elemento).val("");
           });
           $("#saldo").val("0");
           calcularFormaPagoTotales();
        }

   </script>
@endpush


