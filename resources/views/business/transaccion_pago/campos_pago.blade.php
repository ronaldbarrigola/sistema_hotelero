
<div class="card">
    <div class="card-header py-0">
        <strong>DATOS PARA FACTURA</strong>
    </div>
    <div class="card-body py-0">
        <div class="row">

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_cliente_id" class="my-0" ><strong>Cliente:</strong></label>
                    <div class="input-group-append">
                        <select id="pago_cliente_id" name="pago_cliente_id" required class="form-control selectpicker" data-live-search="true">
                            {{--Datos cargados mediante ajax--}}
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_nit" class="my-0"><strong>Nit/Ci:</strong></label>
                    <input type="text" name="pago_nit" id="pago_nit" required maxlength="20" class="form-control">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_nombre" class="my-0"><strong>Nombre para factura:</strong></label>
                    <input type="text" name="pago_nombre" id="pago_nombre" required maxlength="100" class="form-control">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_celular" class="my-0"><strong>Celular:</strong></label>
                    <input type="text" name="pago_celular" id="pago_celular" maxlength="100" class="form-control">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                    <label for="pago_email" class="my-0"><strong>Email:</strong></label>
                    <input type="text" name="pago_email" id="pago_email" maxlength="100" class="form-control">
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
  <script>
      function requiredPago(required){
         $("#pago_cliente_id").prop("required",required);
         $("#pago_nombre").prop("required",required);
         $("#pago_nit").prop("required",required);
         $("#pago_detalle").prop("required",required);
      }
  </script>
@endpush


