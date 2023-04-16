<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group">
            <label for="forma_pago_id" class="my-0" ><strong>Forma de Pago:</strong></label>
            <select name="forma_pago_id" id="forma_pago_id" required class="form-control selectpicker border" data-live-search="true" >
                <!--Se carga los datos por ajax-->
             </select>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="monto_pago" class="my-0"><strong>Total Pago:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>Bs.</strong></span>
                <input type="number" name="monto_pago" id="monto_pago" min="1" required class="form-control" placeholder="0">
            </div>
        </div>
    </div>

</div>
