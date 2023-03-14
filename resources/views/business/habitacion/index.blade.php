@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        @include('business/habitacion/actionbar',['','titulo'=>'HABITACIONES'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {{-- <div class="table-responsive"> --}}
                    <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>Id</th>
                            <th>Numero Hab.</th>
                            <th>Habitacion</th>
                            <th>Piso</th>
                            <th>Precio</th>
                            <th>Tipo Habitacion</th>
                            <th>Hotel</th>
                            <th>Estado Habitacion</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
                {{-- </div> --}}
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/habitacion'])
        @include('business/habitacion/create_edit')
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateHabitacion", function(){ //El boton btnCreateCliente se encuentra en actionbar
                $("#edit").val("");
                $("#title_modal_view_habitacion").text("NUEVA HABITACION");
                limpiarDatoHabitacion();
                $('#modalViewHabitacion').modal('show');
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'codigo'},
                            {data:'habitacion'},
                            {data:'piso'},
                            {data:'precio'},
                            {data:'tipo_habitacion'},
                            {data:'agencia'},
                            {data:'estado_habitacion'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="dataEditHabitacion(this);">Editar</button></a>';
                                }
                            },

                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href='' class='preguntaeliminar btn btn-danger' data-target='#modaleliminar' data-toggle='modal' data-idmodelo='"+data+"'>Eliminar</a>";
                                }
                            },

                        ];
            // ══════════════════════ CARGANDO DataTable por AJAX  ══════════════════════
            datatable_datos=$('#tblListaDatos').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('business/habitacion')}}",
                "columns":columnas
            });

        });//fin ready


    </script>
@endpush
