@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @include('partials/actionbar',['url_nuevo'=>'seguridad/agencias/create?s_id='.$sucursal->id,'url_volver'=>'seguridad/sucursales' , 'titulo'=>'AGENCIAS'])
    @endsection
    {{-- {{$col_usuario!=null?$col_usuario->nombre:'no tiene'}} --}}
    @section('panelCuerpo')
        <input type="hidden" id="s_id" value="{{$sucursal->id}}">
        <div class="card p-0 ">
            <div class="card-header p-0 d-flex justify-content-center">
                AGENCIAS DE SUCURSAL : <span class="text-warning">{{$sucursal->nombre}}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                {{-- <div class="table-responsive"> --}}
                    <table id='tblListaDatos' class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>id</th>
                            <th>Sucursal</th>
                            <th>Agencia</th>
                            <th>Direccion</th>
                            <th>Fono</th>
                            <th>Observacion</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'seguridad/agencias'])
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
                            {data:'sucursal'},
                            {data:'nombre'},
                            {data:'direccion'},
                            {data:'fono'},
                            {data:'observacion'},

                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href={{url('/seguridad/agencias/')}}/"+data+"/edit><button class='btn btn-info'>Editar</button></a>";
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
                "ajax":"{{url('/seguridad/agencias')}}?s_id="+$("#s_id").val(),
                "columns":columnas
            });
            //tabladatos.on( 'error', function () { alert( 'error' );} );
        }
    </script>
@endpush
