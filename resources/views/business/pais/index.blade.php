@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
    @include('business/pais/actionbar',['','titulo'=>'PAISES'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Pais</th>
                        <th>Dominio</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </thead>
                </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/pais'])
        @include('business/pais/create_edit')
    @endsection
@endsection

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreatePais", function(){
                $("#editPais").val("");
                $("#title_modal_view_pais").text("NUEVO PAIS");
                limpiarDatoPais();
                $('#modalViewPais').modal('show');
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'descripcion'},
                            {data:'dominio'},
                            {data:'id',
                                orderable:false,
                                className: "text-center",
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editPais(id);">Editar</button></a>';
                                }
                            },
                            {data:'id',
                                orderable:false,
                                className: "text-center",
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
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('business/pais')}}",
                "columns":columnas
            });

        });//fin ready
    </script>
@endpush
