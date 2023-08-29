@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        @include('business/cliente/actionbar',['','titulo'=>'CLIENTES'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {{-- <div class="table-responsive"> --}}
                    <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>Id</th>
                            <th>Cliente</th>
                            <th>Tipo Persona</th>
                            <th>Nro. Documento</th>
                            <th>Tipo Documento</th>
                            <th>Pais</th>
                            <th>Ciudad</th>
                            <th>Direccion</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
                {{-- </div> --}}
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/cliente'])

        @include('business/cliente/create_edit')

    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateCliente", function(){ //El boton btnCreateCliente se encuentra en actionbar
                createCliente();
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'cliente'},
                            {data:'tipo_persona'},
                            {data:'doc_id'},
                            {data:'tipo_documento'},
                            {data:'pais'},
                            {data:'ciudad'},
                            {data:'direccion'},
                            {data:'telefono'},
                            {data:'email'},
                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return '<button id="'+data+ '" class="btn btn-info" onclick="editCliente(this);">Editar</button></a>';
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
                "ajax":"{{url('business/cliente')}}",
                "columns":columnas
            });

        });//fin ready


    </script>
@endpush
