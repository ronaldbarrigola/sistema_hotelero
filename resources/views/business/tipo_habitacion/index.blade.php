@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @include('business/tipo_habitacion/actionbar',['','titulo'=>'TIPO HABITACIONES'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {{-- <div class="table-responsive"> --}}
                    <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>Sigla</th>
                            <th>Tipo Habitacion</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
                {{-- </div> --}}
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/tipo_habitacion'])
        @include('business/tipo_habitacion/create_edit')
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateTipoHabitacion", function(){ //El boton btnCreateCliente se encuentra en actionbar
               createTipoHabitacion();
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'codigo',className: "text-center"},
                            {data:'tipo_habitacion'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editTipoHabitacion(this);">Editar</button></a>';
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
                "ajax":"{{url('business/tipo_habitacion')}}",
                "columns":columnas
            });

        });//fin ready


    </script>
@endpush
