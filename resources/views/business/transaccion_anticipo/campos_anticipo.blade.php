<input type="hidden" id="anticipo_transaccion_id" name="anticipo_transaccion_id">
<input type="hidden" id="cargo">
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="anticipo" class="my-0"><strong>Monto:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>Bs.</strong></span>
                <input type="number" name="anticipo" id="anticipo" class="form-control" placeholder="0">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="anticipo_forma_pago_id" class="my-0" ><strong>Forma de Pago:</strong></label>
            <div class="input-group-append">
                <select name="anticipo_forma_pago_id" id="anticipo_forma_pago_id" required class="form-control selectpicker border" data-live-search="true" >
                   <!--Se carga los datos por ajax-->
                </select>
            </div>
        </div>
    </div>
</div>

@push('scripts')
  <script>

      $(document).ready(function() {
        $(document).on("keyup", "#anticipo", function(){
           var cargo=$("#cargo").val();
           cargo=(cargo!=null)?cargo:0;
           var anticipo=$("#anticipo").val();
           anticipo=(anticipo!=null)?anticipo:0;

           if(cargo-anticipo<0){
                $("#anticipo").val("")
                messageAlert(`El anticipo no debe ser superior al cargo de : ${cargo} Bs.`);
           }

           $("#fp_monto_base").val(anticipo)//El input se encuentra en el modulo formapago
           calcularFormaPagoTotales();//La funcion de encuentra en el modulo formapago
        });
      });//Fin ready

      function requiredAnticipo(required){
         $("#anticipo_monto").prop("required",required);
      }
  </script>
@endpush

