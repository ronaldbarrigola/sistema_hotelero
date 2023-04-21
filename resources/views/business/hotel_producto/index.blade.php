@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
    @include('business/producto/actionbar',['','titulo'=>'PRODUCTOS'])
    @endsection

    @section('panelCuerpo')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Producto</th>
                        <th>Categoria</th>
                        <th>Precio</th>
                        <th>opcion</th>
                        <th>Modificar</th>
                        <th>Eliminar</th>
                    </thead>
                </table>
            </div>
        </div>
        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'business/producto'])
        @include('business/hotel_producto/create_edit')
    @endsection
@endsection

<!-- ESTILOS Y SCRIPTS -->
{{-- para formatear fecha --}}
@include('partials/datetimepicker')

@push('scripts')
    <script>
        $(document).ready( function () {

            $(document).on("click", "#btnCreateProducto", function(){ //El boton btnCreateProducto se encuentra en actionbar
                createHotelProducto();
            });

            // ══════════════════════ Cargando columnas para datatables  ══════════════════════
            var columnas=[
                            {data:'id'},
                            {data:'producto'},
                            {data:'categoria'},
                            {data:'precio', className: "text-center"},
                            {data:'producto_id',
                                className: "text-center",
                                orderable:false,
                                render: function(data, type, row){
                                     if(row.id==null){
                                        return '<button id="'+row.producto_id+ '" class="btn btn-primary" onclick="activateHotelProducto(id);">Activar</button>';
                                     } else {
                                        return "ACTIVADO"; //Siempre de debe colocar valor de retorno por falso sino da error
                                     }
                                }
                            },
                            {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function(data, type, row){
                                    if(row.id!=null){
                                        return '<button id="'+data+ '" class="btn btn-info" onclick="editHotelProducto(this);">Editar</button>';
                                     } else {
                                        return "";  //Siempre de debe colocar valor de retorno por falso sino da error
                                     }
                                }
                            },
                            {data:'id',
                                className: "text-center",
                                orderable:false,
                                render: function(data, type, row){
                                    if(row.id!=null){
                                        return '<button id="'+data+ '" class="btn btn-danger" onclick="deleteHotelProducto(id);">Eliminar</button>';
                                     } else {
                                        return "";  //Siempre de debe colocar valor de retorno por falso sino da error
                                     }
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
                "ajax":"{{url('business/hotel_producto')}}",
                "columns":columnas
            });

        });//fin ready
    </script>
@endpush
