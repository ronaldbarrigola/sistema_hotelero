<!DOCTYPE html>
<html lang="{{ app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--  esto para evitar que al usar selector dropdowslist de bootstrap no deforme la pagina al desplazarse hacia abajo cuando existia cadenas largas  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximun-scale=1.0, minimun-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Laravel 8') }}</title> -->
    <title>{{ App\Entidades\Base\Empresa::nombreEmpresa() }}</title>

    <!-- Styles -->
    <!-- Mensajes -->
    <link href="{{asset('css/toastr.min.css') }}" rel="stylesheet">
    <!-- Bootstrap 4.6 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}">

     {{-- <link href="{{ asset('css/jquery/sm/jquery.smartmenus.bootstrap-4.css') }}" rel="stylesheet"> --}}
     <link href="{{ asset('css/bootstrap/bootnavbar.css') }}" rel="stylesheet">
     <link href="{{ asset('css/bootstrap/navbarcolor.css') }}" rel="stylesheet">
     <link href="{{ asset('css/icomoon/style.css') }}" rel="stylesheet">
     <link href="{{ asset('css/bootstrap/bootstrap-select.min.css') }}" rel="stylesheet">
     <link href="{{ asset('css/jquery/datatables.min.css') }}" rel="stylesheet">
     <link href="{{ asset('css/css.css') }}" rel="stylesheet">
     <link href="{{ asset('css/bootstrap4-toggle.css') }}" rel="stylesheet">
     <link rel="stylesheet" href="{{asset('css/bspersonalizado.css')}}">

     <!-- TimeLine -->
     <link href="{{ asset('css/timeline/sweetalert.css') }}" rel="stylesheet">
     <link href="{{ asset('css/timeline/vis-timeline-graph2d.min.css') }}" rel="stylesheet">
     <link href="{{ asset('css/timeline/css_timeline.css') }}" rel="stylesheet">

     @stack('estilos')
</head>
<body style="background-color:lightgray">

    <div id="app" >
        <div class="bg-dark py-0">
            <div class="container py-0 d-flex justify-content-center">
                <div>
                    <span class="text-white px-1">SUCURSAL: </span><span class="text-warning">{{session()->get("SUCURSAL_NOMBRE")}}</span>
                </div>
                <div>
                    <span class="text-white px-1"> - HOTEL: </span><span class="text-warning">{{session()->get("AGENCIA_NOMBRE")}}</span>
                </div>
            </div>
        </div>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark my-0 py-0">
            <div class="container">
                {{-- CABECERA --}}
                <a class="navbar-brand" href="{{ url('/') }}"><span class="icon-home"></span> INICIO</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                {{-- ----------------------- --}}
                <div class="collapse navbar-collapse" id="navbarMain">

                    <!-- Left nav -->
                    {{-- <ul id="main-menu" class="nav navbar-nav mr-auto" data-sm-skip="true " data-sm-options="{ subIndicators: false}"> --}}
                    <ul id="main-menu" class="nav navbar-nav mr-auto">

                    </ul>

                    <!-- Right nav -->
                    <ul class="nav navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Ingresar</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown">
                                    {{-- <i class="icon-user"></i> {{ Auth::user()->persona->nombre }} --}}
                                    <i class="icon-user"></i> {{ session()->get('USUARIO_NOMBRE')}}
                                    <span class="sub-arrow d-md-none"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('editpass') }}" class="dropdown-item">
                                                <span class="icon-eye-blocked"></span> Cambiar Password
                                        </a>
                                        <form id="pass-form" action="{{ route('editpass') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}" class="dropdown-item"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <span class="icon-cerrar"></span> Cerrar Sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                </div>
            </div>
        </nav>

        <div id="main_content" class="container fondo-blanco rounded paddingminimo" style="display: none">
            @yield('content')
        </div>

        <div id="main_content_index" class="fondo-blanco rounded paddingminimo" style="display: none"> <!--Quitando el container el despliegue ocupa toda la pantalla-->
            @yield('content_index')
        </div>

    </div>

    <!-- Scripts -->
    <!-- CONSTANTE URL_BASE y URL_PUBLICA para usar en invocaciones ajax y otros -->
    <script>var URL_BASE = {!! json_encode(url('/')) !!};</script>
    <script>var URL_PUBLICA = {!! json_encode(asset('')) !!};</script>
    <!-- jQuery 3.5 -->
    <script src="{{asset('js/jquery/jquery-3.5.1.min.js')}}"></script>
    <!-- popper -->
    <script src="{{asset('js/popper.min.js')}}"></script>
    <!-- Bootstrap 4.6 -->
    <script src="{{asset('js/bootstrap/bootstrap.min.js')}}"></script>
    <!-- Mensajes -->
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <script src="{{asset('js/bootstrap/bootnavbar.js')}}"></script>
    <script src="{{asset('js/bootstrap/bootstrap-select.min.js')}}"></script>
    {{-- VALIDADOR DE INPUTS FORM --}}
    <script src="{{asset('js/bootstrap/validarformulario.js')}}"></script>
    <script src="{{asset('js/jquery/datatables.min.js')}}"></script>
    <script src="{{asset('js/bootstrap/boot4alert.js')}}"></script>
    <script src="{{asset('js/bootstrap4-toggle.js')}}"></script>

    <!-- TimeLine -->
    <script src="{{asset('js/timeline/sweetalert.min.js')}}"></script>
    <script src="{{asset('js/timeline/vis-timeline-graph2d.min.js')}}"></script>

    <script>
        //toastr.options.toastClass = 'toastr'; //esta  opcion es para corregir el error de mensaje borroso..
        toastr.options = {
                "toastClass":"toastr", //esta  opcion es para corregir el error de mensaje borroso..
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                 "positionClass": "toast-bottom-full-width",
                //"positionClass" : "toast-top-center",
                "preventDuplicates": true,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": 0,
                "extendedTimeOut": 0,
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "tapToDismiss": false
                };
    </script>

    {{-- ################ FIN GENERANDO MENUS ################ --}}
    <script>
        $(document).ready(function(){
             cargarListaMenus('{{session()->get("ROL_ID")}}');
             return false;
        });//fin ready

        //plegando el menu al hacer clic en la opcion.
        function contraerMenu(){
            var $navBar=$("nav.navbar .show");
            $navBar.removeClass('show');
        }
        //=====================================================================================================================
        //Cargando lista de menus por AJAX
        //=====================================================================================================================
        function cargarListaMenus(idModelo){
            url=URL_BASE+"/seguridad/rolmenu/lista_menus";
            // $("#rol_id").val(idModelo);
            $('#main-menu').find('*').remove();// limpiando contenido de lista.
            $.get(url,"idRol="+idModelo,function(JsonDato){
                var menuRaiz={"id": null, "nombre": "MENU","asignado":0,"url":""};//creando en nodo raiz, con nombre menu
                var li=generarMenusRecursivo(JsonDato,menuRaiz);
                $("#main-menu").html(li);
                //$("#main-menu").removeAttr("data-sm-skip");
                /////////////$('#main-menu').smartmenus();
                //$.SmartMenus.Bootstrap.init();
                $('#navbarMain').bootnavbar(); // iniciando plugin js  // tiene que ejecutarse luego de cargar el DOM
            },'json');
        }
        //=====================================================================================================================
        // GENERANDO LISTA DE MENU CON CSS RECURSIVAMENTE
        //=====================================================================================================================
        function generarMenusRecursivo(JsonDato,menuPadre){
            //filtrando submenus
            var subMenus=$.grep(JsonDato,function(item,index){
                return item.padre_id==menuPadre.id;
            });
            var lista='';
            if(subMenus.length>0){
                var menuClass=menuPadre.padre_id==null?'nav-link':'dropdown-item';
                menuClass=menuClass+' dropdown-toggle';
                var nodo='<a href="#" class="'+menuClass+'" role="button" data-toggle="dropdown">'+
                            '<i class="'+menuPadre.icono+'"> </i> '+menuPadre.nombre+
                            '<span class="sub-arrow d-md-none"></span>'+
                        '</a>';
                lista=lista+'<li class="nav-item dropdown"> '+nodo+' <ul class="dropdown-menu">';
                if(menuPadre.id==null){lista='';}
                for(var i=0;i<subMenus.length;i++){
                    lista=lista+ generarMenusRecursivo(JsonDato,subMenus[i]);
                }// fin for
                if(menuPadre.id!=null){
                    lista=lista+"</ul></li>";
                }

            }else{
                var nodo='<a href="'+URL_BASE+menuPadre.url+'" class="dropdown-item" onclick="contraerMenu();">'+
                        '<i class="'+menuPadre.icono+'"> </i> '+menuPadre.nombre+
                    '</a>';
                lista= '<li class="nav-item"> '+nodo+'</li>';
            }
            return lista;
        }//fin funcion

    </script>
    {{-- ################ FIN GENERANDO MENUS ################ --}}
    @stack('scripts')
</body>
</html>
