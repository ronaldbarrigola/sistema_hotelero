@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        {{App\Entidades\Base\Sistema::nombreVersion()}}
    @endsection

    @section('panelCuerpo')
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <button id="enfo">ir a id</button>
        <div id="visualization"></div>
        <div id="log"></div>

        @include('business/reserva/create_edit')
        @include('business/cliente/create_edit')
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

            //evita mostrar menu contextual del navegador al presionar click derecho.
            timeline.on('contextmenu', function (props) {
                alert('mostrar menu contextual');
                props.event.preventDefault();
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
                        console.log(result);
                        $.each(result.reservas,function(i, v) {
                            let tipoGrafico='range';
                            if(v.servicio_id==2) //DAY USE
                            {
                                tipoGrafico='point';
                            }
                            dataItems.push({id:v.id,title:v.cliente,content:v.paterno,start:v.fecha_ini,end:v.fecha_fin,group:v.habitacion_id,className: 'bg-info text-white',type:tipoGrafico})
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
                    callback(item); // send back adjusted new item

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
                    // prettyPrompt('Desea modificar la reserva del cliente : ' + item.content, function (value) {
                    // if (value) {
                    //     // item.content = value;
                    //     // callback(item); // send back adjusted item
                    // }
                    // else {
                    //     callback(null); // cancel updating the item
                    // }
                    // });

                    editReserva(item.id);

                },

                //---------- EVENTO ELIMINAR ITEM -----------------------
                onRemove: function (item, callback) {
                    prettyConfirm('Eliminar Reserva', 'Esta seguro de eliminar la reserva ' + item.content + '?', function (ok) {
                    if (ok) {
                        callback(item); // confirm deletion
                        deleteReserva(item.id)
                    }
                    else {
                        callback(null); // cancel deletion
                    }
                    });
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

    </script>
@endpush


<script src="{{asset('js/datetime/moment.min.js')}}"></script>
<script src="{{asset('js/datetime/es.js')}}"></script>
