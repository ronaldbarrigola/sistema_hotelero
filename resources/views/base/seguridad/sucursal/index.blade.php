@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @include('partials/actionbar',['url_nuevo'=>'seguridad/sucursales/create','titulo'=>'SUCURSALES'])
    @endsection
    {{-- {{$col_usuario!=null?$col_usuario->nombre:'no tiene'}} --}}
    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {{-- <div class="table-responsive"> --}}
                    <table id='tblListaDatos' class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>id</th>
                            <th>Nombre</th>
                            <th>Ciudad</th>
                            <th>Observación</th>
                            <th>Agencias</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'seguridad/sucursales'])
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}

@push('scripts')
    <script>
        $(document).ready( function () {
            cargarListaDatos();
        });//fin ready

        // ==============================================================================================
        // Cargando listaDatos
        //==============================================================================================
        function cargarListaDatos(){
            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'nombre'},
                            {data:'ciudad'},
                            {data:'observacion'},

                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href={{url('/seguridad/sucursales/')}}/"+data+"><button class='btn btn-primary'>Agencias</button></a>";
                                }
                            },

                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href={{url('/seguridad/sucursales/')}}/"+data+"/edit><button class='btn btn-info'>Editar</button></a>";
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
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('/seguridad/sucursales')}}",
                "columns":columnas
            });
            //tabladatos.on( 'error', function () { alert( 'error' );} );
        }
    </script>
@endpush
