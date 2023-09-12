@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
    @include('business/categoria/actionbar',['','titulo'=>'CATEGORIAS'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <th>Categoria</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </thead>
                </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/categoria'])
        @include('business/categoria/create_edit')
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateCategoria", function(){ //El boton btnCreateCategoria se encuentra en actionbar
                $("#edit").val("");
                $("#title_modal_view_categoria").text("NUEVA CATEGORIA");
                limpiarDatoCategoria();
                $('#modalViewCategoria').modal('show');
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'descripcion'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="dataEditCategoria(this);">Editar</button></a>';
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
                "ajax":"{{url('business/categoria')}}",
                "columns":columnas
            });

        });//fin ready
    </script>
@endpush
