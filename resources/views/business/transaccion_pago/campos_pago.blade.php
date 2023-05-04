
<div class="card">
    <div class="card-header py-0">
        <strong>DATOS GENERALES</strong>
    </div>
    <div class="card-body py-0">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_nombre" class="my-0"><strong>Nombre:</strong></label>
                    <input type="text" name="pago_nombre" id="pago_nombre" required class="form-control">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_nit" class="my-0"><strong>Nit/Ci:</strong></label>
                    <input type="text" name="pago_nit" id="pago_nit" required class="form-control">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_email" class="my-0"><strong>Email:</strong></label>
                    <input type="text" name="pago_email" id="pago_email" class="form-control">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_detalle" class="my-0"><strong>Concepto:</strong></label>
                    <input type="text" name="pago_detalle" id="pago_detalle" required class="form-control">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
  <script>
      function requiredPago(required){
         $("#pago_nombre").prop("required",required);
         $("#pago_nit").prop("required",required);
         $("#pago_detalle").prop("required",required);
      }
  </script>
@endpush


