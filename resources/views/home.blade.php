@extends('layouts.plantillaFormExtendido')
@section('contenido')
    @section('panelCabecera')
        {{App\Entidades\Base\Sistema::nombreVersion()}}
    @endsection


    @section('panelCuerpo')
        <div id="visualization"></div>
        <div id="log"></div>

        @push('scripts')
        <script type="text/javascript">
            // DOM element where the Timeline will be attached
                var container = document.getElementById('visualization');

            //fecha limite inferior y superior para el timeline.
            var min = new Date(2023, 0, 1); //
            var max = new Date(2023, 5, 31); //
            // CREANDO HABITACIONES en formato json, puede venir de ajax.
            var groups = new vis.DataSet([
            { id: 0, content: 'PISO 1', nestedGroups: [1, 2] },
            { id: 1, content: '101', title: "HABITACION 1 SIMPLE" },
            { id: 2, content: '102' },
            { id: 3, content: '103' },
            { id: 4, content: '104' },
            { id: 5, content: '105' },
            { id: 6, content: '201' },
            { id: 7, content: 'Restaurant' },
            { id: 8, content: 'Eventos' },
            ]);
            // CREANDO ITEMS EN FORMATO JSON, PUEDE VENIR DE AJAX
            var items = new vis.DataSet([
            { id: 1, title: 'TITULO PRUEBA', content: 'Item 1', start: '2023-02-08T00:00:00', end: '2023-02-09T00:00:00', group: 1, className: 'bg-info text-white' },
            { id: 2, content: "Item 2", start: '2023-02-08T12:00:00', end: '2023-02-09T12:00:00', group: 2, style: 'color:#ffffff; background-color:#ec0dd2' },
            { id: 3, content: 'Item 3', start: '2023-02-09T12:00:00', end: '2023-02-11T12:00:00', group: 1, className: 'bg-primary text-white' },
            { id: 4, content: 'Item 4', start: '2023-02-10T12:00:00', end: '2023-02-11T12:00:00', group: 4, className: 'bg-primary text-white',value:0.2 },
            { id: 5, content: 'Item 5 en mantenimiento', start: min, end: max, group: 3, className: 'bg-danger text-white', type: "background" },
            { id: 6, content: 'item 6', start: '2023-02-11T14:00:00', end: '2023-02-15T14:00:00', group: 5, value: 0.72, visibleFrameTemplate: '', className: 'bg-primary text-white' },
            { id: 7, content: 'item 7', group: 1, start: '2023-02-13', end: '2023-02-14', group: 7, visibleFrameTemplate: '<div class="progress-wrapper"><div class="progress" style="width:80%"></div><label class="progress-label">80%<label></div>' },
            { id: 8, content: 'Item 8', group: 7, start: '2023-02-14', end: '2023-02-15', group: 8, editable: false },

            ]);

            //#################### Configuration for the Timeline ####################

            var options = {
            editable: true,
            stack: true,
            //showCurrentTime: true,

            visibleFrameTemplate: function (item) {
                if (item == null) return;//evitando error al crear rango(al presionar tecla crtl y arrastrar)
                // if (item.visibleFrameTemplate != '') {
                // return item.visibleFrameTemplate;// si ya tiene definido el visibleFrameTemplate lo muestra
                // }
                //si el visibleFrameTemplate no tiene cotenido se calcula
                var percentage = item.value * 100 + '%';
                return '<div class="progress-wrapper"><div class="progress" style="width:' + percentage + '"></div><label class="progress-label">Pago de ' + percentage + '<label></div>';
            },

            // template: //function (item) {
            //   //return '<h1>' + item.header + '</h1><p>' + item.description + '</p>';
            //   var percentage = item.value * 100 + '%';
            //   return '<div class="progress-wrapper"><div class="progress" style="width:' + percentage + '"></div><label class="progress-label">' + percentage + '<label></div>';
            // },


            // locale: 'en',


            timeAxis: { scale: 'day', step: 1 },

            format: {
                minorLabels: { day: "DD" },
                majorLabels: { month: 'MMMM' }
            },
            // format: {
            //   minorLabels: function (date, scale, step) {
            //     var dia_literal = date.format("dd");
            //     var dia_numeral = date.format("DD");
            //     return "<div>" + "dia_literal" + "<br>" + "dia_numeral" + "</div>";
            //   },
            //   majorLabels: function (date, scale, step) { return date.format("MMMM") }
            // },

            orientation: {
                axis: "both",
                item: "top"
            },
            width: '100%',
            // height: '500px',
            // moveable :true,
            // zoomable :false,
            // zoomKey:'altKey',
            // horizontalScroll:true,
            // verticalScroll:true,

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
                prettyPrompt('Add item', 'Enter text content for new item:', item.content, function (value) {
                if (value) {
                    item.content = value;
                    if (item.end == null) {
                    // solo ingresa aca cuando se añade con doble click , por tanto no se tiene fecha final(box) y se debe convertir en rango(range)
                    item.start.setHours(12);
                    item.start.setMinutes(0);
                    item.start.setSeconds(0);
                    var f = item.start;
                    item.end = new Date(f.getFullYear(), f.getMonth(), f.getDate() + 1, 12, 0, 0);
                    }
                    callback(item); // send back adjusted new item
                }
                else {
                    callback(null); // cancel item creation
                }
                });
            },

            //---------- EVENTO MOVER ITEM -----------------------
            onMove: function (item, callback) {
                var title = 'Do you really want to move the item to\n' +
                'start: ' + item.start + '\n' +
                'end: ' + item.end + '?';

                prettyConfirm('Move item', title, function (ok) {
                if (ok) {
                    callback(item); // send back item as confirmation (can be changed)
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
                prettyPrompt('Update item', 'Edit items text:', item.content, function (value) {
                if (value) {
                    item.content = value;
                    callback(item); // send back adjusted item
                }
                else {
                    callback(null); // cancel updating the item
                }
                });
            },

            //---------- EVENTO ELIMINAR ITEM -----------------------
            onRemove: function (item, callback) {
                prettyConfirm('Remove item', 'Do you really want to remove item ' + item.content + '?', function (ok) {
                if (ok) {
                    callback(item); // confirm deletion
                }
                else {
                    callback(null); // cancel deletion
                }
                });
            },
            //-----------------------------------------------------------

            };//####################FIN OPCIONES ####################

            var timeline = new vis.Timeline(container, items, groups, options);

            //-----------------------------------------------------------
            items.on('*', function (event, properties) {
            logEvent(event, properties);
            //console.log(properties.items[0]);
            });

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
            // add event listener
            timeline.on('select', onSelect);

            //evita mostrar menu contextual del navegador al presionar click derecho.
            timeline.on('contextmenu', function (props) {
            alert('mostrar menu contextual');
            props.event.preventDefault();
            });

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
    @endsection
@endsection

{{-- @include('partials/datetimepicker') --}}


<script src="{{asset('js/datetime/moment.min.js')}}"></script>
<script src="{{asset('js/datetime/es.js')}}"></script>
