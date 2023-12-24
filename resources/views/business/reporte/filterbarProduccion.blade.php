<header>
    <form  method="GET" action="{{url('/business/reporte/exportar_produccion')}}" autocomplete="off">
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

            <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                <div class="form-group m-0">
                    <label for="producto_id" class="my-0"><strong>Producto:</strong></label>
                    <select id="producto_id" name="producto_id"  class="form-control selectpicker" data-live-search="true">
                        <option value="">--Todos--</option>
                        @foreach($productos as $listProducto)
                            <option value="{{$listProducto->id}}">{{$listProducto->producto}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="fecha_ini" class="my-0"><strong>Desde:</strong></label>
                    <input type="date" name="fecha_ini" id="fecha_ini" class="form-control">
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="fecha_fin" class="my-0"><strong>Hasta:</strong></label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
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
            var today=fechaActual();
            $("#fecha_ini").val(today);
            $("#fecha_fin").val(today);

            $("#btnExportarPdf").on( "click", function() {
                $('#formato').val('pdf');
            });

            $("#btnExportarExcel").on( "click", function() {
                $('#formato').val('excel');
            });
        });//fin ready
    </script>
@endpush

@include('partials/utilesjs')





