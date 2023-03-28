    <input type="hidden" name="reserva_id" id="reserva_id">
    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>RESERVA</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="cliente_id" class="my-0" ><strong>Reservado por:</strong></label>
                        <div class="input-group-append">
                            <select name="cliente_id" id="cliente_id" required class="form-control selectpicker border" data-live-search="true" >
                               <!--Se carga los datos por ajax-->
                            </select>
                            <button type="button" id="btnModalInfoCliente" class="input-group-btn btn btn-light"><span class="icon-table2"></span></button>
                            <button type="button" id="btnModalCreateCliente" class="input-group-btn btn btn-light"><span class="icon-plus"></span></button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="habitacion_id" class="my-0" ><strong>Habitacion:</strong></label>
                        <select name="habitacion_id" id="habitacion_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="paquete_id" class="my-0" ><strong>Paquete:</strong></label>
                        <select name="paquete_id" id="paquete_id"  class="form-control selectpicker border" data-live-search="true" >
                             <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="num_adulto" class="my-0"><strong>Numeros de adultos:</strong></label>
                        <input type="number" name="num_adulto" id="num_adulto" max="99" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="num_nino" class="my-0"><strong>Numero de Niños:</strong></label>
                        <input type="number" name="num_nino" id="num_nino" max="99" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="procedencia_pais_id" class="my-0" ><strong>Pais Procedencia:</strong></label>
                        <select name="procedencia_pais_id" id="procedencia_pais_id"  class="form-control selectpicker border" data-live-search="true" >
                             <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                    <label for="procedencia_ciudad_id" class="my-0" ><strong>Ciudad Procedencia:</strong></label>
                    <select name="procedencia_ciudad_id" id="procedencia_ciudad_id"  class="form-control selectpicker border" data-live-search="true" >
                          <!--Se carga los datos por ajax-->
                    </select>
                    </div>
                </div>
           </div>
        </div>
    </div>

    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>SERVICIO</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">
                 <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="servicio_id" class="my-0" ><strong>Servicio:</strong></label>
                        <select name="servicio_id" id="servicio_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="motivo_id" class="my-0" ><strong>Motivo:</strong></label>
                        <select name="motivo_id" id="motivo_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="fecha_ini"  class="my-0"><strong>Fecha Ingreso:</strong></label>
                        <input type="date" id="fecha_ini" name="fecha_ini" required class="form-control">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="fecha_fin"  class="my-0"><strong>Fecha Salida:</strong></label>
                        <input type="date" id="fecha_fin" name="fecha_fin" required class="form-control">
                    </div>
                </div>
           </div>
        </div>
    </div>

    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>CARGO</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="monto" class="my-0"><strong>Precio por Unidad:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bs.</span>
                            <input type="number" name="monto" id="monto" class="form-control" required placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="descuento" class="my-0"><strong>Descuento %:</strong></label>
                        <input type="number" name="descuento" id="descuento" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="total" class="my-0"><strong>Total Cargo:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text">Bs.</span>
                            <input type="number" name="total" id="total" readonly class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="detalle" class="my-0"><strong>Detalle:</strong></label>
                <input type="text" name="detalle" id="detalle" class="form-control">
            </div>
        </div>
    </div>


