@extends('layouts.plantillaForm')
@section('contenido')
    @section('panelCabecera')
        @include('partials/actionbar',['url_nuevo'=>'seguridad/usuarios/create_edit/null','titulo'=>'USUARIOS'])
    @endsection
    @section('panelCuerpo')

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <table id="tblListaDatos" class="table table-striped table-bordered table-sm table-hover">
                        <thead>
                            <th>id</th>
                            <th>Login</th>
                            <th>Email Corporativo</th>
                            <th>Modificar</th>
                            <th>Resetear Password</th>
                            <th>Eliminar</th>
                            <th>Nombre</th>
                            <th>Ap. Paterno</th>
                            <th>Ap. Materno</th>
                            <th>Sexo</th>
                            <th>Fecha Nac.</th>
                            <th>Tipo Doc.</th>
                            <th>Num. Doc.</th>
                            <th>Exp.</th>
                            <th>Email Personal</th>
                            <th>Telefono</th>
                            <th>Dirección</th>
                        </thead>

                    </table>
            </div>
        </div>

        @include('partials/confirmaeliminacion',['url_base_eliminar'=>'seguridad/usuarios'])

    @endsection
@endsection


{{-- para formatear fecha --}}
@include('partials/datetimepicker')
@push('scripts')
    <script src="{{asset('js/utilesdatatable.js')}}"></script>
    <script>
        var datatable_datos;
        $(document).ready( function () {
            cargarListaDatos();
            busquedaPorColumna("#tblListaDatos",datatable_datos, [3,4,5]);
        });//fin ready

        // ==============================================================================================
        // Cargando listaDatos
        //==============================================================================================
        function cargarListaDatos(){
            // ══════════════════════ columnas para datatables  ══════════════════════
            var columnas=[  {data:'u.id'},
                            {data:'u.login'},
                            {data:'u.email'},

                            {data:'u.id',orderable:false,
                                render: function(data){
                                    return "<a href={{url('/seguridad/usuarios/create_edit')}}/"+data+"><button class='btn btn-info'>Editar</button></a>";
                                }
                            },

                            {data:'u.id',orderable:false,
                                render: function(data,type,full,meta){
                                    return "<a href='#' id='btnResetPass' class='btn btn-warning' data-idmodelo="+data+" data-usulogin="+full.u.login+">ResetPass</a>";
                                }
                            },

                            {data:'u.id',
                                orderable:false,
                                render: function(data){
                                    return "<a href='' class='preguntaeliminar btn btn-danger' data-target='#modaleliminar' data-toggle='modal' data-idmodelo='"+data+"'>Eliminar</a>";
                                }
                            },

                            {data:'p.nombre'},
                            {data:'p.paterno'},
                            {data:'p.materno'},
                            {data:'s.nombre'},
                            {data:'p.fecha_nac',
                                render: function(data){
                                    return moment(data).format("DD/MM/YYYY");
                                }
                            },
                            {data:'t.abreviacion'},//tipo doc id
                            {data:'p.doc_id'},
                            {data:'c.abreviacion'},//ciudad
                            {data:'p.email'},
                            {data:'p.telefono'},
                            {data:'p.direccion'},


                        ];
            // ══════════════════════ CARGANDO DataTable por AJAX  ══════════════════════
            datatable_datos=$('#tblListaDatos').DataTable({
                "processing":true,
                "language": {"url":"{{asset('js/jquery/datatables.spanish.json')}}"},
                "iDisplayLength": 5,
                "dom": 's<"table-responsive"tr><"bottom float-left"p><"clearfix">',
                "serverSide":true,
                "order": [[ 0, "desc" ]],
                "ajax":"{{url('/seguridad/usuarios')}}",
                "columns":columnas,
                // columnDefs: [ {"targets": [ 0, 2,16 ],"defaultContent": "?"} ]
                columnDefs: [ {"targets": "_all","defaultContent": "?"} ]//poniendo signo "?"" ante valores nulos y columnas no definidas
            });
        }
        // ======= FIN cargarListaDatos =================================================================

        // ========= RESET PASS
        $('#tblListaDatos').on( "click","#btnResetPass", function(e) {
            var id =$(this).data('idmodelo');
            var reset_p =$(this).data('usulogin');
            boot4.confirm({
                msg:"¿esta seguro de resetear password del usuario con id:"+id+" ?",
                title:"Confirmacion",
                callback:function(result){

                    if(result){
                        url_update_pass=URL_BASE+"/seguridad/usuarios/updatepass";
                        console.log(url_update_pass);
                        $.ajax({
                            type: "POST",
                            url: url_update_pass,
                            data:{'_token': '{{csrf_token()}}','usuario_id':id,'password':reset_p},//data:{'_method':'POST','_token': '{{ csrf_token() }}','password':login},
                            dataType: 'json',
                            //contentType: 'application/json',
                            success: function(result){
                                    toastr.info("\n\npassword reseteado. El password es igual que el login.\n\n");
                            },
                            error:function(resultado){
                                toastr.error("\n\nerror al resetear password.\n\n");
                            }
                        });

                    }//fin if

                }//fin callback
            });//fin confirm

        });
        //fin reset pass
    </script>

@endpush

