@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @include('partials/actionbar',['url_nuevo'=>'_modal_nuevo_','titulo'=>'ROLES'])
    @endsection
    @include('base/seguridad/roles/create_edit')
    @include('base/seguridad/roles/asignar_menu')
    @section('panelCuerpo')
    
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>Id</th>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Menus</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'seguridad/roles'])
    @endsection
@endsection
    
    
@push('scripts')
    <script src="{{asset('js/utilesjs.js')}}"></script>
    <script>
        // btnMostrarModalNuevo
        var datatable_datos;
        $(document).ready( function () {
           cargarListaDatos();
           //busquedaPorColumna("#tblListaDatos",datatable_datos, [0,1,14,15,16]);
        } );//fin ready

        // ==============================================================================================
        // Cargando listaDatos
        //==============================================================================================
        function cargarListaDatos(){
            // ══════════════════════ columnas para datatables  ══════════════════════
            var columnas=[  {data:'id'},
                            {data:'codigo'},
                            {data:'nombre'},
                            {data:'descripcion'},

                            {data:'id',orderable:false,
                                render: function(data,type,fila,meta){
                                    return "<button data-idmodelo='"+data+"' data-rolnombre='"+fila.nombre+"' class='btnMostrarModalAsignarMenu btn btn-primary'>Menus</button>";
                                }
                            },
                            {data:'id',orderable:false,
                                render: function(data){
                                    return "<button data-idmodelo='"+data+"' class='btnMostrarModalModificar btn btn-info'>Editar</button>";
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
                "iDisplayLength": 5,
                "dom": 's<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('/seguridad/roles')}}",
                "columns":columnas
            });
        }
       
    </script>
@endpush

