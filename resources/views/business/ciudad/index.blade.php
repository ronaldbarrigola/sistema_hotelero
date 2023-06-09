@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
    @include('business/ciudad/actionbar',['','titulo'=>'CIUDAD'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <th>Ciudad</th>
                        <th>Pais</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </thead>
                </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/ciudad'])
        @include('business/ciudad/create_edit')
    @endsection
@endsection

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateCiudad", function(){
                $("#editCiudad").val("");
                $("#title_modal_view_ciudad").text("NUEVA CIUDAD");
                limpiarDatoCiudad();
                $('#modalViewCiudad').modal('show');
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'ciudad'},
                            {data:'pais'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editCiudad(this);">Editar</button></a>';
                                }
                            },
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href='' class='preguntaeliminar btn btn-danger' data-target='#modaleliminar' data-toggle='modal' data-idmodelo='"+data+"'>Eliminar</a>";
                                }
                            },
                        ];

            datatable_datos=$('#tblListaDatos').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 10,
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                //"order": [[ 0, "desc" ]],
                "ajax":"{{url('business/ciudad')}}",
                "columns":columnas
            });

        });//fin ready
    </script>
@endpush
