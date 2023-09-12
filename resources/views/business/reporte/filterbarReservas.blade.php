<header>
    <form  method="GET" action="{{url('/business/reporte/exportar_reservas')}}" autocomplete="off">
        @csrf
        <div class="row">

            <input type="hidden" name="formato" id="formato">

            <div class="col-lg-1 col-md-1 col-sm-1 col-12">
                <div class="form-group m-0">
                    <label for="habitacion_id" class="my-0"><strong>Habitacion:</strong></label>
                    <select id="habitacion_id" name="habitacion_id"  class="form-control selectpicker" data-live-search="true">
                        <option value="">--Todos--</option>
                        @foreach($habitaciones as $listHabitacion)
                           <option value="{{$listHabitacion->id}}">{{$listHabitacion->num_habitacion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-1 col-md-1 col-sm-1 col-12">
                <div class="form-group m-0">
                    <label for="tipo_habitacion_id" class="my-0"><strong>Tipo Hab.:</strong></label>
                    <select id="tipo_habitacion_id" name="tipo_habitacion_id"  class="form-control selectpicker" data-live-search="true">
                        <option value="">--Todos--</option>
                        @foreach($tipo_habitacion as $listTipoHabitacion)
                           <option value="{{$listTipoHabitacion->id}}">{{$listTipoHabitacion->tipo_habitacion}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="cliente_id" class="my-0"><strong>Cliente:</strong></label>
                    <select id="cliente_id" name="cliente_id"  class="form-control selectpicker" data-live-search="true">
                        <option value="">--Todos--</option>
                        @foreach($clientes as $listCliente)
                            <option value="{{$listCliente->id}}">{{$listCliente->cliente}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-1 col-md-1 col-sm-1 col-12">
                <div class="form-group m-0">
                    <label for="estado_reserva_id" class="my-0"><strong>Estado:</strong></label>
                    <select id="estado_reserva_id" name="estado_reserva_id"  class="form-control selectpicker" data-live-search="true">
                        <option value="">--Todos--</option>
                        @foreach($estado_reserva as $listEstadoReserva)
                            <option value="{{$listEstadoReserva->id}}">{{$listEstadoReserva->estado_reserva}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="fecha_ini" class="my-0"><strong>Desde:</strong></label>
                    <input type="text" name="fecha_ini" id="fecha_ini" data-target="#fecha_ini"
                        data-toggle="datetimepicker" class="form-control datetimepicker-input datetimepicker_calendario"
                        value="" placeholder="Desde el Primero">
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="fecha_fin" class="my-0"><strong>Hasta:</strong></label>
                    <input type="text" name="fecha_fin" id="fecha_fin" data-target="#fecha_fin"
                        data-toggle="datetimepicker" class="form-control datetimepicker-input datetimepicker_calendario"
                        value="" placeholder="Hasta el Ultimo">
                </div>
            </div>

            <div class="col-lg-1 col-md-1 col-sm-1 col-12">
                <div class="form-group m-0">
                    <label for="btnFiltrar" class="my-0"><strong></strong></label>
                    <button type="button" id="btnFiltrar" class="form-control  input-group-btn btn btn-primary">Filtrar</button>
                </div>
            </div>

            <div class="col-lg-1 col-md-1 col-sm-1 col-12">
                <div class="form-group m-0">
                    <label for="btnExportarPdf" class="my-0"><strong></strong></label>
                    <button id="btnExportarPdf" class="form-control  input-group-btn btn btn-danger">Pdf</button>
                </div>
            </div>

            <div class="col-lg-1 col-md-1 col-sm-1 col-12">
                <div class="form-group m-0">
                    <label for="btnExportarExcel" class="my-0"><strong></strong></label>
                    <button id="btnExportarExcel" class="form-control  input-group-btn btn btn-success">Excel</button>
                </div>
            </div>

        </div>
    </form>
</header>

@push('scripts')
    <script>
        $(document).ready( function () {
            $("#fecha_ini").val(moment().format('DD/MM/YYYY'));
            $("#fecha_fin").val(moment().format('DD/MM/YYYY'));

            $("#btnExportarPdf").on( "click", function() {
                $('#formato').val('pdf');
            });

            $("#btnExportarExcel").on( "click", function() {
                $('#formato').val('excel');
            });
        });//fin ready
    </script>
@endpush



