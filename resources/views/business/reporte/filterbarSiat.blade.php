<header>
    <form  method="GET" action="{{url('/business/reporte/exportar_siat')}}" autocomplete="off">
        @csrf
        <div class="row">

            <input type="hidden" name="formato" id="formato">

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="fecha_ini" class="my-0"><strong>Desde:</strong></label>
                    <input type="date" name="fecha_ini" id="fecha_ini" required class="form-control">
                </div>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-12">
                <div class="form-group m-0">
                    <label for="fecha_fin" class="my-0"><strong>Hasta:</strong></label>
                    <input type="date" name="fecha_fin" id="fecha_fin" required class="form-control">
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
            //Establecer fecha actual
            var today=fechaActual();
            $("#fecha_ini").val(today);
            $("#fecha_fin").val(today);

            $("#btnFiltrar").on( "click", function() {
                datatable_datos.ajax.reload();//recargar registro datatables.
            });

        });//fin ready

    </script>
@endpush

@include('partials/utilesjs')



