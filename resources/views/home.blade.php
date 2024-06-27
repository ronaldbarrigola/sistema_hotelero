@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
       <div class="cabecera_principal">
            {{App\Entidades\Base\Sistema::nombreVersion()}}
       </div>
       <div class="cabecera_transaccion" style="display:none">
            @include('business/transaccion/actionbar',['','titulo'=>'CARGOS'])
       </div>
       <div class="cabecera_huesped" style="display:none">
            @include('business/huesped/actionbar',['','titulo'=>'HUESPED'])
       </div>
    @endsection

    @section('panelCuerpo')
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="carouselReserva carousel slide" data-ride="carousel" data-interval="false" data-touch="false">
            <div class="carousel-inner">
                <div class="carousel-item active" id="slide-1">
                    {{-- Contenedor principal timeline --}}
                    <div id="visualization"></div>
                </div>
                <div class="carousel-item" id="slide-2">
                    @include('business/transaccion/datatable_transaccion')
                </div>
                <div class="carousel-item" id="slide-3">
                    @include('business/huesped/datatable_huesped')
                </div>
            </div>
        </div>

        @include('business/reserva/create_edit')
        @include('business/cliente/create_edit')
        @include('business/profesion/create_edit')
        @include('business/grupo/create_edit')
        @include('business/contextmenu/menu')
    @endsection
@endsection

@push('scripts')
    <script type="text/javascript">

        //Documnentacion time line www.visjs.org

        var container = document.getElementById('visualization');
        var num_dias_por_pantalla=container.clientWidth/60;//60px por cada dia

        //Variables Time Line
        var dataGroups=[];
        var dataItems=[];
        var selectedItems=[]; //Usado para agrupar items seleccionados
        var groups="";
        var items ="";
        var options="";
        var timeline="";

        //Calcular fecha minimo y maximo para el timeline
        var fecha = new Date();
        var anio = fecha.getFullYear();
        var anio_min=anio - 1;
        var anio_max=anio + 1;
        var min = new Date(anio_min, 0, 1);
        var max = new Date(anio_max, 12, 31);

        $(document).ready(function(){

            loadGroups(); //Cargar Habitaciones
            loadItems(); //Cargar reservas realizadas
            loadOptions();
            timeline = new vis.Timeline(container, items, groups, options);

            //Para agrupar reservas seleccionados
            timeline.on('select', function (properties) {
                selectedItems = properties.items;
            });

            timeline.on('contextmenu', function (props) {
                props.event.preventDefault(); // Para evitar que se abra el menú del navegador
                var $menu = $('.context-menu'); //.context-menu se ecuentra en el modulo contextmenu
                $menu.empty();
                if(props.item!=null){
                    //BEGIN: Insertar elementos al menu contextual
                    var btnCargos="<div class='col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='slideTransaccion(id)' style='text-align:left'>Cargos</button></div>"; //slideReservaTransaccion(id) se encuentra en el modulo transaccion.crete_edit
                    var btnHuesped="<div class='m=0 col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='slideHuesped(this)' style='text-align:left'>Huesped</button></div>";
                    var btnUpdate="<div class='m=0 col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='editReserva(id)' style='text-align:left'>Modificar</button></div>";
                    var btnCheckIn="<div class='m=0 col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='checkIn(this)' style='text-align:left'>Check In</button></div>";
                    var btnCheckOut="<div class='col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='checkOut(this)' style='text-align:left'>Check Out</button></div>";
                    var btnStandBy="<div class='col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='standBy(this)' style='text-align:left'>Stand By</button></div>";
                    var btnGroupSelectedItems="<div class='col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='groupSelectedItems(id)' style='text-align:left'>Agrupar</button></div>";

                    $menu.append(btnCargos);
                    $menu.append(btnHuesped);
                    $menu.append(btnUpdate);
                    $menu.append(btnCheckIn);
                    $menu.append(btnCheckOut);
                    $menu.append(btnStandBy);
                    $menu.append(btnGroupSelectedItems);

                    //END: Insertar elementos al menu contextual se ecuentra en el modulo contextmenu
                } else {
                    var btnNuevaReserva="<div class='col-12'><button type='button' id='"+props.item+"' data-habitacion_id='"+ props.group +"' data-fecha_ini='"+ addDaysToDate(props.time,1) +"' data-fecha_fin='"+ addDaysToDate(props.time,2) +"' class='form-control btn btn-light' onclick='nuevaReserva(this)' style='text-align:left'>Nueva Reserva</button></div>";
                    $menu.append(btnNuevaReserva);
                }

                $menu.css({
                    display: 'block',
                    left: props.event.pageX,
                    top: props.event.pageY
                });

            });

        }); //End ready

        function loadGroups(){
            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('obtenerHabitaciones')}}",
                data:{'_token':'{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    var estilo="";
                    if(result.response){
                        $.each(result.habitaciones,function(i, v) {
                            dataGroups.push({id:v.id,content:v.num_habitacion + " " + v.codigo,style:v.estilo})
                        });
                        groups = new vis.DataSet(dataGroups);
                    }

                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function loadItems(){
            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('obtenerReservasTimeLine')}}",
                data:{'_token':'{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    if(result.response){
                        $.each(result.reservas,function(i, v) {
                            let tipoGrafico='range';

                            var nombre="";
                            var title="";
                            var tipo_habitacion="";


                            var nombre_grupo=(v.nombre_grupo!=""&&v.nombre_grupo!=null)?`<br><span>Grupo : ${v.nombre_grupo}</span>`:"";

                            if(v.tipo_persona_id=="J"){ //J:Persona Juridica N:Persona Natural
                                nombre=v.nombre;
                            } else {
                                nombre=v.paterno;
                            }

                            title=`<span>Nro. Reserva : ${v.id}</span><br><span>Cliente : ${v.cliente}</span><br><span>Tipo Habitacion : ${v.tipo_habitacion}</span><br><span>Canal Reserva : ${v.canal_reserva}</span>${nombre_grupo}`;

                            if(v.estado_reserva_id==0||v.estado_reserva_id==1){
                                nombre =v.cantidad_huesped_checkin + ", " + nombre
                                var porcentaje=(v.porcentaje!=null)?v.porcentaje:0;
                                var visibleFrameTemplate='<div class="progress-wrapper"><div class="progress" style="width:'+porcentaje+'%"></div><label class="progress-label">'+porcentaje+'%<label></div>';
                                dataItems.push({id:v.id,content:nombre,title:title,start:v.fecha_ini,end:v.fecha_fin,group:v.habitacion_id,className:v.color,style: "border: 5px solid " + v.color_borde ,editable:Boolean(v.editable),visibleFrameTemplate:visibleFrameTemplate})
                            } else {
                                dataItems.push({id:v.id,content:nombre,title:title,start:v.fecha_ini,end:v.fecha_fin,group:v.habitacion_id,className:v.color,style: "border: 5px solid " + v.color_borde ,editable:Boolean(v.editable)})
                            }

                        });

                        items = new vis.DataSet(dataItems);
                    }

                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function updateItemForId($id){
            $.ajax({
                async: false, //Evitar la ejecucion  Asincrona
                type: "GET",
                url: "{{route('obtenerReservaPorIdTimeLines')}}",
                data:{reserva_id:$id,'_token':'{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    if(result.response){
                        var nombre="";
                        var title="";
                        var v=result.reserva;
                        if(v.tipo_persona_id=="J"){ //J:Persona Juridica N:Persona Natural
                            nombre=v.nombre;
                        } else {
                            nombre=v.paterno;
                        }

                        var nombre_grupo=(v.nombre_grupo!=""&&v.nombre_grupo!=null)?`<br><span>Grupo : ${v.nombre_grupo}</span>`:"";
                        title=`<span>Nro. Reserva : ${v.id}</span><br><span>Cliente : ${v.cliente}</span><br><span>Tipo Habitacion : ${v.tipo_habitacion}</span><br><span>Canal Reserva : ${v.canal_reserva}</span>${nombre_grupo}`;

                        if(v.estado_reserva_id==0||v.estado_reserva_id==1){
                            nombre=v.cantidad_huesped_checkin + ", " + nombre
                            var porcentaje=(v.porcentaje!=null)?v.porcentaje:0;
                            var visibleFrameTemplate='<div class="progress-wrapper"><div class="progress" style="width:'+porcentaje+'%"></div><label class="progress-label">'+porcentaje+'%<label></div>';
                            items.update({id:v.id,content:nombre,title:title,start:v.fecha_ini,end:v.fecha_fin,group:v.habitacion_id,className:v.color,style: "border: 5px solid " + v.color_borde,editable:Boolean(v.editable),visibleFrameTemplate:visibleFrameTemplate});
                        } else {
                            items.update({id:v.id,content:nombre,title:title,start:v.fecha_ini,end:v.fecha_fin,group:v.habitacion_id,className:v.color,style: "border: 5px solid " + v.color_borde,editable:Boolean(v.editable)})
                        }
                       //timeline.redraw();
                       //timeline.refresh();
                    }

                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function loadOptions(){
            var fechaActual = new Date(); //Para que el timeline se ubique en la fecha actual
            options = {
                 multiselect: true,
                 selectable: true,
                 start: fechaActual,
                 end: fechaActual,
                 editable: true,
                 stack: true,
                 visibleFrameTemplate: function (item) {
                    if (item == null) return;//evitando error al crear rango(al presionar tecla crtl y arrastrar)
                    if (item.visibleFrameTemplate != '') {
                        return item.visibleFrameTemplate;// si ya tiene definido el visibleFrameTemplate lo muestra
                    }
                    //si el visibleFrameTemplate no tiene cotenido se calcula
                    var percentage = item.value * 100 + '%';
                    return '<div class="progress-wrapper"><div class="progress" style="width:' + percentage + '"></div><label class="progress-label">Pago de ' + percentage + '<label></div>';
                },
                timeAxis: { scale: 'day', step: 1 },
                format: {
                    minorLabels: { day: "DD" },
                    majorLabels: { month: 'MMMM' }
                },
                orientation: {
                    axis: "both",
                    item: "top"
                },
                width: '100%',
                min: min,                // lower limit of visible range
                max: max,                // upper limit of visible range
                zoomMin: 1000 * 60 * 60 * 24 * 10 * 1,// one day in milliseconds
                zoomMax: 1000 * 60 * 60 * 24 * 20 * 1,// about three months in milliseconds

                //-----------  always snap to full hours, independent of the scale ------------------------------------------------
                snap: function (date, scale, step) {
                    var hour = 60 * 60 * 1000;
                    return Math.round(date / hour) * hour;
                },

                //---------- EVENTO ADICIONAR ITEM -----------------------
                onAdd: function (item, callback) {
                    if (item.end == null) {
                        item.start.setHours(12);
                        item.start.setMinutes(0);
                        item.start.setSeconds(0);
                        var f = item.start;
                        item.end = new Date(f.getFullYear(), f.getMonth(), f.getDate() + 1, 12, 0, 0);
                    }
                    var fecha_ini=item.start;
                    var fecha_fin=item.end;
                    var habitacion_id=item.group;
                    createReserva(); //Visualizar formulario modal reserva, se encuentra en reserva.crete_edit
                    setDateReserva(fecha_ini,fecha_fin); //la funcion setDateReserva, se encuentra en reserva.crete_edit
                    setHabitacion(habitacion_id) //la funcion setHabitacion se, encuentra en reserva.crete_edit
                    callback(null); //Para que desaparesca el item por defecto
                },

                //---------- EVENTO MOVER ITEM -----------------------
                onMove: function (item, callback) {
                    var title = 'Se modificara las fechas y el cargo \n' +
                    'Fecha Ingreso: ' + formatFecha(item.start) + '\n' +
                    'Fecha Salida: ' + formatFecha(item.end) + '?';

                    prettyConfirm('Modificar reserva', title, function (ok) {
                    if (ok) {
                        editReserva(item.id);
                        var fecha_ini=item.start;
                        var fecha_fin=item.end;
                        var habitacion_id=item.group;
                        setHabitacion(habitacion_id)         //la funcion setHabitacion, se encuentra en reserva.crete_edit
                        setDateReserva(fecha_ini,fecha_fin); //la funcion setDateReserva, se encuentra en reserva.crete_edit
                    }
                    else {
                        callback(null); // cancel editing item
                    }
                    });
                },

                //---------- EVENTO MOVIENDO ITEM -----------------------
                onMoving: function (item, callback) {
                    if (item.start < min) item.start = min;
                    if (item.start > max) item.start = max;
                    if (item.end > max) item.end = max;

                    callback(item); // send back the (possibly) changed item
                },

                //---------- EVENTO ACTUALIZANDO ITEM -----------------------
                onUpdate: function (item, callback) {
                    editReserva(item.id);
                },

                //---------- EVENTO ELIMINAR ITEM -----------------------
                onRemove: function (item, callback) {
                    $.ajax({
                        async: false, //Evitar la ejecucion  Asincrona
                        type: "GET",
                        url: "{{route('validar_eliminacion')}}",
                        data:{reserva_id:item.id,'_token':'{{ csrf_token() }}'},
                        dataType: 'json',
                        success: function(result){
                            if(result.response){
                                prettyConfirm("Eliminar Reserva","Esta seguro de eliminar la reserva?", function (ok) {
                                    if (ok) {
                                        executeDeleteReserva(item.id);
                                        callback(item); // confirm delete
                                    } else {
                                        callback(null); //Cancel delete
                                    }
                                });
                            } else {
                                messageAlert(result.message);
                                callback(null); //Cancel delete
                            }

                        }
                    }); //End Ajax
                },
            };
        }

        //=============MODAL DE CONFIRMACION================
        function prettyConfirm(title, text, callback) {
            swal({
                title: title,
                text: text,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55"
            }, callback);
        }

        function checkIn($this){
            prettyConfirm("Check In","Esta seguro de ejecutar la accion Check In?", function (ok) {
                if (ok) {
                    var reserva_id=$this.id;
                        var estado_reserva_id=1;//Check In
                        estadoReserva(reserva_id,estado_reserva_id);
                }
            });
        }

        function standBy($this){
            prettyConfirm("Stand By","Esta seguro de ejecutar la accion Stand By", function (ok) {
                if (ok) {
                    var reserva_id=$this.id;
                    var estado_reserva_id=2;//Stand By
                    estadoReserva(reserva_id,estado_reserva_id);
                }
            });
        }

        function groupSelectedItems($id){
            if (selectedItems.length > 0) {
                limpiarDatoGrupo();
                $.ajax({
                    async: false, //Evitar la ejecucion  Asincrona
                    type: "GET",
                    url: "{{route('obtener_grupo_reserva')}}",
                    data:{reserva_id:$id,selected_items:selectedItems,'_token':'{{ csrf_token() }}'},
                    dataType: 'json',
                    success: function(result){
                        var items=result.reservas;
                        var grupo=result.grupo;
                        if(result.accion=="modificar"){
                            $("#grupoId").val(grupo.id);
                            $("#editGrupo").val("modificar");
                            $("#title_modal_view_grupo").text("MODIFICAR GRUPO");
                            $("#nombre_grupo").val(grupo.nombre);
                            $("#color_grupo").val(grupo.color);
                        } else {
                            $("#title_modal_view_grupo").text("NUEVO GRUPO");
                        }

                        if (items && items.length > 0) {
                            items.forEach(function(v) {
                                cargarFilaGrupo(v.reserva_id,v.num_habitacion,v.estado)
                            });
                        }
                    }
                }); //End Ajax

                $("#modalViewGrupo").modal("show");
            } else {
                messageAlert("Debe seleccionar uno o mas reservas para agrupar");
            }
        }

        function checkOut($this){
            prettyConfirm("Check Out","Esta seguro de ejecutar la accion Check Out", function (ok) {
                if (ok) {
                    var reserva_id=$this.id;
                    var estado_reserva_id=3;//Check Out
                    estadoReserva(reserva_id,estado_reserva_id);
                }
            });
        }

        function estadoReserva(reserva_id,estado_reserva_id){
            $.ajax({
                type: "POST",
                url: "{{route('estadoreserva')}}",
                data:{reserva_id:reserva_id,estado_reserva_id:estado_reserva_id,'_token':'{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){

                   if(result.response){
                        var item = items.get(reserva_id);
                        items.update({id: reserva_id,className:result.reserva.color});
                   } else {
                    messageAlert(result.message);
                   }
                },//End success
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                }, //END error
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        function nuevaReserva($his){
            var fecha_ini=$($his).data("fecha_ini");
            var fecha_fin=$($his).data("fecha_fin");
            var habitacion_id=$($his).data("habitacion_id");
            createReserva(); //Visualizar formulario modal reserva, se encuentra en reserva.crete_edit
            setDateReserva(fecha_ini,fecha_fin); //la funcion setDateReserva, se encuentra en reserva.crete_edit
            setHabitacion(habitacion_id) //la funcion setHabitacion se, encuentra en reserva.crete_edit
        }

    </script>
@endpush

<script src="{{asset('js/datetime/moment.min.js')}}"></script>
<script src="{{asset('js/datetime/es.js')}}"></script>
