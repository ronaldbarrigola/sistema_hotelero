@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @include('partials/actionbar',['url_nuevo'=>'seguridad/personas/create_edit/null','titulo'=>'PERSONAS'])
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
                            <th>Ap. Paterno</th>
                            <th>Ap. Materno</th>
                            <th>Sexo</th>
                            <th>Fecha Nac.</th>
                            <th>Tipo Doc.</th>
                            <th>Num. Doc.</th>
                            <th>Exp.</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Dirección</th>
                            @if ($col_usuario!=null) {{-- laravel --}}
                                <th>Adm. Usuario</th>
                            @endif
                            <th>Adm. Vendedor</th>
                            <th>Modificar</th>
                            <th>Eliminar</th>
                        </thead>
                    </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'seguridad/personas'])
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')
@push('scripts')
    <script>
        $(document).ready( function () {
            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'nombre'},
                            {data:'paterno'},
                            {data:'materno'},
                            {data:'sexo'},
                            {data:'fecha_nac',
                                render: function(data){
                                    return moment(data).format("DD/MM/YYYY");
                                }
                            },
                            {data:'tipo_doc'},
                            {data:'doc_id'},
                            {data:'ciudad_exp'},
                            {data:'email'},
                            {data:'telefono'},
                            {data:'direccion'},

                            @if($col_usuario!=null) //laravel
                                {data:'id',
                                    orderable:false,
                                    render: function(data,type,fila,meta){
                                        var class_boton="btn btn-warning";//adicion
                                        var texto_boton="+Usuario"
                                        if(fila.usuario_activado){
                                            class_boton="btn btn-primary";//modificacion
                                            texto_boton="Usuario"
                                        }

                                        return "<a class='"+class_boton+"' href={{url('/seguridad/usuarios/create_edit')}}/"+data+">"+texto_boton+"</a>";
                                    }
                                },
                            @endif

                            {data:'id',
                                orderable:false,
                                render: function(data,type,fila,meta){
                                    var class_boton="btn btn-success";//adicion
                                    var texto_boton="+Vendedor"
                                    if(fila.vendedor_activado){
                                        class_boton="btn btn-secondary";//modificacion
                                        texto_boton="Vendedor"
                                    }
                                    return "<a class='"+class_boton+"' href={{url('/venta/vendedores/create_edit')}}/"+data+">"+texto_boton+"</a>";
                                }
                            },

                            {data:'id',
                                orderable:false,
                                render: function(data){
                                    return "<a href={{url('/seguridad/personas/create_edit')}}/"+data+"><button class='btn btn-info'>Editar</button></a>";
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
                //  "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                // fixedHeader: {
                //     header: true,
                //     footer: true
                // },
                // 'responsive':true,
                //dom:'Bfrtilp',
                // "dom": '<"top pull-left"f><"clearfix"><"table-responsive"tr><"bottom pull-left"p><"clearfix">',
                "dom": '<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                //"dom": '<"pull-left"l>f<"clearfix"><"table-responsive"tr><"pull-left"ip><"clearfix">',

                //$("div.toolbar").html('<b>Custom tool bar! Text/images etc.</b>');
                // 'buttons': ['copy', 'excel', 'pdf'
                //         ],
                    // "serching": true,
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('/seguridad/personas')}}",
                "columns":columnas

            });
            //tabladatos.on( 'error', function () { alert( 'error' );} );
        });//fin ready
    </script>
@endpush
