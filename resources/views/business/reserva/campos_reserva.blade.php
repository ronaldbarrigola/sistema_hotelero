    <input type="hidden" name="reserva_id" id="reserva_id">
    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>RESERVA</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="cliente_id" class="my-0" ><strong>Reservado por:</strong></label>
                        <select name="cliente_id" id="cliente_id"  class="form-control selectpicker border" required data-live-search="true" >
                        <option value="">--Seleccione--</option>
                        @foreach($clientes as $lista_clientes)
                            <option value="{{$lista_clientes->id}}"> {{$lista_clientes->cliente}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="habitacion_id" class="my-0" ><strong>Habitacion:</strong></label>
                        <select name="habitacion_id" id="habitacion_id"  class="form-control selectpicker border" required data-live-search="true" >
                        <option value="">--Seleccione--</option>
                        @foreach($habitaciones as $lista_habitacion)
                            <option value="{{$lista_habitacion->id}}" data-precio="{{$lista_habitacion->precio}}">{{$lista_habitacion->num_habitacion}} {{$lista_habitacion->tipo_habitacion}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="paquete_id" class="my-0" ><strong>Paquete:</strong></label>
                        <select name="paquete_id" id="paquete_id"  class="form-control selectpicker border" data-live-search="true" >
                        <option value="">--Seleccione--</option>
                        @foreach($paquetes as $lista_paquete)
                            <option value="{{$lista_paquete->id}}"> {{$lista_paquete->descripcion}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="num_adulto" class="my-0"><strong>Numeros de adultos:</strong></label>
                        <input type="number" name="num_adulto" id="num_adulto" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="num_nino" class="my-0"><strong>Numero de Niños:</strong></label>
                        <input type="number" name="num_nino" id="num_nino" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="procedencia_pais_id" class="my-0" ><strong>Pais:</strong></label>
                        <select name="procedencia_pais_id" id="procedencia_pais_id"  class="form-control selectpicker border" data-live-search="true" >
                        <option value="">--Seleccione--</option>
                        @foreach($paises as $lista_pais)
                            <option value="{{$lista_pais->id}}"> {{$lista_pais->descripcion}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                    <label for="procedencia_ciudad_id" class="my-0" ><strong>Ciudad:</strong></label>
                    <select name="procedencia_ciudad_id" id="procedencia_ciudad_id"  class="form-control selectpicker border" data-live-search="true" >

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
                 <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="producto_id" class="my-0" ><strong>Servicio:</strong></label>
                        <select name="producto_id" id="producto_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <option value="">--Seleccione--</option>
                            @foreach($productos as $lista_producto)
                                <option value="{{$lista_producto->id}}"> {{$lista_producto->producto}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                    <div class="form-group">
                        <label for="fecha_ini"  class="my-0"><strong>Fecha Ingreso:</strong></label>
                        <input type="date" id="fecha_ini" name="fecha_ini" required class="form-control">
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
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


