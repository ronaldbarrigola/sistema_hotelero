<div class="card">
    <div class="card-header py-0">
        <strong>DATOS ANTICIPO</strong>
    </div>
    <div class="card-body py-0">
        <div class="row">

            <input type="hidden" id="anticipo_cargo">

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="anticipo_monto" class="my-0"><strong>Monto:</strong></label>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><strong>Bs.</strong></span>
                        <input type="number" name="anticipo_monto" id="anticipo_monto" class="form-control" placeholder="0">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="anticipo_saldo" class="my-0"><strong>Saldo:</strong></label>
                    <input type="text" id="anticipo_saldo" readonly class="form-control">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="anticipo_detalle" class="my-0"><strong>Detalle:</strong></label>
                    <input type="text" name="anticipo_detalle" id="anticipo_detalle" class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
  <script>

      $(document).ready(function() {
        $(document).on("keyup", "#anticipo_monto", function(){
           var cargo=$("#anticipo_cargo").val();
           cargo=(cargo!=null)?cargo:0;
           var monto=$("#anticipo_monto").val();
           monto=(monto!=null)?monto:0;

           var saldo=cargo-monto;
           if(saldo<0){
                $("#anticipo_monto").val(cargo)
                $("#anticipo_saldo").val(0);
                messageAlert(`El anticipo no debe ser superior al cargo : ${cargo} Bs.`);
           } else {
                $("#anticipo_saldo").val(parseFloat(saldo).toFixed(2));
           }
           $("#fp_monto_base").val(monto)//El input se encuentra en el modulo formapago
           calcularFormaPagoTotales();//La funcion de encuentra en el modulo formapago
        });
      });//Fin ready

      function requiredAnticipo(required){
         $("#anticipo_monto").prop("required",required);
         $("#anticipo_detalle").prop("required",required);
      }
  </script>
@endpush

