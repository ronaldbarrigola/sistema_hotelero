@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
       <div class="cabecera_principal">
            {{App\Entidades\Base\Sistema::nombreVersion()}}
       </div>
       <div class="cabecera_transaccion" style="display:none">
            @include('business/transaccion/actionbar',['','titulo'=>'CARGOS'])
       </div>
    @endsection

    @section('panelCuerpo')
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="carouselReserva carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    {{-- Contenedor principal timeline --}}
                    <div id="visualization"></div>
                </div>
                <div class="carousel-item">
                    @include('business/transaccion/datatable_transaccion')
                </div>
            </div>
        </div>

        @include('business/reserva/create_edit')
        @include('business/cliente/create_edit')
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
        var groups="";
        var items ="";
        var options="";
        var timeline="";

        var min = new Date(2023, 0, 1); //
        var max = new Date(2023, 5, 31); //

        $(document).ready(function(){

            loadGroups(); //Cargar Habitaciones
            loadItems(); //Cargar reservas realizadas
            loadOptions();
            timeline = new vis.Timeline(container, items, groups, options);

            // add event listener
            timeline.on('select', onSelect);

            timeline.on('contextmenu', function (props) {
                props.event.preventDefault(); // Para evitar que se abra el menú del navegador

                if(props.item!=null){
                    //BEGIN: Insertar elementos al menu contextual se ecuentra en el modulo contextmenu
                    var $menu = $('.context-menu');
                    $menu.empty();
                    var btnCargos="<div class='col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='slideReservaTransaccion(id)'>Cargos</button></div>"; //slideReservaTransaccion(id) se encuentra en el modulo transaccion.crete_edit
                    var btnCheckIn="<div class='m=0 col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='checkIn(this)'>Check In</button></div>";
                    var btnCheckOut="<div class='col-12'><button type='button' id='"+props.item+"' class='form-control btn btn-light' onclick='checkOut(this)'>Check Out</button></div>";

                    $menu.append(btnCargos);
                    $menu.append(btnCheckIn);
                    $menu.append(btnCheckOut);

                    $menu.css({
                        display: 'block',
                        left: props.event.pageX,
                        top: props.event.pageY
                    });
                    //END: Insertar elementos al menu contextual se ecuentra en el modulo contextmenu
                }

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
                    if(result.response){
                        $.each(result.habitaciones,function(i, v) {
                            dataGroups.push({id:v.id,content:v.num_habitacion})
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
                url: "{{route('obtenerReservas')}}",
                data:{'_token':'{{ csrf_token() }}'},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function(result){
                    if(result.response){
                        $.each(result.reservas,function(i, v) {
                            let tipoGrafico='range';
                            if(v.servicio_id==2) //DAY USE
                            {
                                tipoGrafico='point';
                            }
                            dataItems.push({id:v.id,content:v.paterno,start:v.fecha_ini,end:v.fecha_fin,group:v.habitacion_id,className:v.color,visibleFrameTemplate: function(itemData, timelineData) {var percentComplete = (timelineData.currentTime - itemData.start) / (itemData.end - itemData.start);var width = percentComplete * 100 + '%';return '<div class="progress-bar" style="width: ' + width + ';"></div>';},type:tipoGrafico})
                        });

                        items = new vis.DataSet(dataItems);
                    }

                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

        // function loadItems(){
        //     // CREANDO ITEMS EN FORMATO JSON, PUEDE VENIR DE AJAX
        //     items = new vis.DataSet([
        //                 { id: 1, title: 'TITULO PRUEBA', content: 'Item 1', start: '2023-02-08T00:00:00', end: '2023-02-09T00:00:00', group: 1, className: 'bg-info text-white' },
        //                 { id: 2, content: "Item 2", start: '2023-02-08T12:00:00', end: '2023-02-09T12:00:00', group: 2, style: 'color:#ffffff; background-color:#ec0dd2' },
        //                 { id: 3, content: 'Item 3', start: '2023-02-09T12:00:00', end: '2023-02-11T12:00:00', group: 1, className: 'bg-primary text-white' },
        //                 { id: 4, content: 'Item 4', start: '2023-02-10T12:00:00', end: '2023-02-11T12:00:00', group: 4, className: 'bg-primary text-white' },
        //                 { id: 5, content: 'Item 5 en mantenimiento', start: min, end: max, group: 3, className: 'bg-danger text-white', type: "background" },
        //                 { id: 6, content: 'item 6', start: '2023-02-11T14:00:00', end: '2023-02-15T14:00:00', group: 5, value: 0.72, visibleFrameTemplate: '', className: 'bg-primary text-white' },
        //                 { id: 7, content: 'item 7', group: 1, start: '2023-02-13', end: '2023-02-14', group: 7, visibleFrameTemplate: '<div class="progress-wrapper"><div class="progress" style="width:80%"></div><label class="progress-label">80%<label></div>' },
        //                 { id: 8, content: 'Item 8', group: 7, start: '2023-02-14', end: '2023-02-15', group: 8, editable: false },

        //                 ]);
        //     items.on('*', function (event, properties) {
        //         logEvent(event, properties);
        //         //console.log(properties.items[0]);
        //     });
        // }

        function loadOptions(){
            options = {
                editable: true,
                stack: true,
                //showCurrentTime: true,

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
                zoomMin: 1000 * 60 * 60 * 24 * 10 * 1,             // one day in milliseconds
                zoomMax: 1000 * 60 * 60 * 24 * 20 * 1,     // about three months in milliseconds

                //-----------  always snap to full hours, independent of the scale ------------------------------------------------
                snap: function (date, scale, step) {
                    var hour = 60 * 60 * 1000;
                    return Math.round(date / hour) * hour;
                },

                //---------- EVENTO ADICIONAR ITEM -----------------------
                onAdd: function (item, callback) {
                    if (item.end == null) {
                        // solo ingresa aca cuando se a���ade con doble click , por tanto no se tiene fecha final(box) y se debe convertir en rango(range)
                        //pedir cuantas noches se quedara
                        item.start.setHours(12);
                        item.start.setMinutes(0);
                        item.start.setSeconds(0);
                        var f = item.start;
                        item.end = new Date(f.getFullYear(), f.getMonth(), f.getDate() + 1, 12, 0, 0);
                    }

                    var fecha_ini=item.start;
                    var fecha_fin=item.end;
                    var habitacion_id=item.group;

                    createReserva(); //Visualizar formulario modal reserva, se encuentra en reserva/crete_edit
                    setDateReserva(fecha_ini,fecha_fin); //la funcion setDateReserva, se encuentra en reserva/crete_edit
                    selectHabitacion(habitacion_id) //la funcion selectHabitacion se, encuentra en reserva/crete_edit
                    //callback(item); // send back adjusted new item
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
                        setDateReserva(fecha_ini,fecha_fin); //la funcion setDateReserva, se encuentra en reserva/crete_edit
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
                    // prettyConfirm('Eliminar Reserva', 'Esta seguro de eliminar la reserva ' + item.content + '?', function (ok) {
                    // if (ok) {
                    //     callback(item); // confirm deletion
                    //     deleteReserva(item.id)
                    // }
                    // else {
                    //     callback(null); // cancel deletion
                    // }
                    // });
                    callback(item); // confirm deletion
                    deleteReserva(item.id)
                },
            };
        }

        function logEvent(event, properties) {
            var log = document.getElementById('log');
            var msg = document.createElement('div');
            msg.innerHTML = 'event=' + JSON.stringify(event) + ', ' +
                'properties=' + JSON.stringify(properties);
            log.firstChild ? log.insertBefore(msg, log.firstChild) : log.appendChild(msg);
        }

        //=========== EVENTOS TIMELINE================================
        function onSelect(properties) {
            //prettyMessage(properties.toString());
        }

        function prettyMessage(text) {
            swal(text);
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

        //======MODAL PIDIENDO DATOS============================
        function prettyPrompt(title, text, inputValue, callback) {
            swal({
                title: title,
                text: text,
                type: 'input',
                showCancelButton: true,
                inputValue: inputValue
            }, callback);
        }

        function checkIn($this){
            var reserva_id=$this.id;
            var estado_reserva_id=1;
            estadoReserva(reserva_id,estado_reserva_id);
        }

        function checkOut($this){
            var reserva_id=$this.id;
            var estado_reserva_id=3;
            estadoReserva(reserva_id,estado_reserva_id);
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
                        //items.update({id: reserva_id,className: 'bg-primary text-white'});
                        items.update({id: reserva_id,className:result.reserva.color});

                   } else {
                    messageAlert(result.message);
                   }
                },//End success
                complete:function(result, textStatus ){

                }
            }); //End Ajax
        }

    </script>
@endpush


<script src="{{asset('js/datetime/moment.min.js')}}"></script>
<script src="{{asset('js/datetime/es.js')}}"></script>
