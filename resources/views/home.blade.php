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
            var container="";
            var dataGroups=[];
            var groups="";
            var items ="";
            var options="";

            $(document).ready(function(){
                //Ruta documentacion TimeLine www.visjs.org

                container = document.getElementById('visualization');
                var num_dias_por_pantalla=container.clientWidth/60;//60px por cada dia

                loadGroups();

                //GRUPOS
                // var groups = new vis.DataSet([
                //   { id: 0, content: 'HAb 100'},
                //   { id: 1, content: 'Hab 101', title: "HABITACION 1 SIMPLE" },
                //   { id: 2, content: 'Hab 102' },
                //   { id: 3, content: 'Hab 103' },
                //   { id: 4, content: 'Hab 104' },
                //   { id: 5, content: 'Hab 105' },
                //   { id: 6, content: 'Hab 201' },
                // ]);

                //  var groups = new vis.DataSet([
                //   { "id": 0,"content": 'Hab 100'},
                //   { "id": 1,"content": 'Hab 101'},
                //   { "id": 2,"content": 'Hab 102' },
                //   { "id": 3,"content": 'Hab 103' },
                //   { "id": 4,"content": 'Hab 104' },
                //   { "id": 5,"content": 'Hab 105' },
                //   { "id": 6,"content": 'Hab 201' },
                // ]);





               // var timeline = new vis.Timeline(container,items,groups,options);

                //-----------------------------------------------------------


                // function logEvent(event, properties) {
                //   var log = document.getElementById('log');
                //   var msg = document.createElement('div');
                //   msg.innerHTML = 'event=' + JSON.stringify(event) + ', ' +
                //     'properties=' + JSON.stringify(properties);
                //   log.firstChild ? log.insertBefore(msg, log.firstChild) : log.appendChild(msg);
                // }

                // //=========== EVENTOS TIMELINE================================
                // function onSelect(properties) {
                //   //prettyMessage(properties.toString());
                // }
                // // add event listener
                // timeline.on('select', onSelect);

                // //evita mostrar menu contextual del navegador al presionar click derecho.
                // timeline.on('contextmenu', function (props) {
                //     props.event.preventDefault();
                //   prettyMessage("Clic derecho: tam: ancho:"+container.clientWidth+"px  Alto:"+container.clientHeight+"px");

                // });

                // function prettyMessage(text) {
                //   swal(text);
                // }
                // //=============MODAL DE CONFIRMACION================
                // function prettyConfirm(title, text, callback) {
                //   swal({
                //     title: title,
                //     text: text,
                //     type: 'warning',
                //     showCancelButton: true,
                //     confirmButtonColor: "#DD6B55"
                //   }, callback);
                // }

                // //======MODAL PIDIENDO DATOS============================
                // function prettyPrompt(title, text, inputValue, callback) {
                //   swal({
                //     title: title,
                //     text: text,
                //     type: 'input',
                //     showCancelButton: true,
                //     inputValue: inputValue
                //   }, callback);
                // }

            }); //End ready

            function loadGroups(){
                $.ajax({
                    type: "GET",
                    url: "{{route('obtenerHabitaciones')}}",
                    data:{'_token':'{{ csrf_token() }}'},
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    success: function(result){
                        if(result.response){
                            $.each(result.habitaciones,function(i, v) {
                                dataGroups.push({id:v.id,content:v.codigo})
                            });
                            //console.log(dataGroups);
                            groups = new vis.DataSet(dataGroups);
                        }

                    },//End success
                    complete:function(result, textStatus ){


                       items = new vis.DataSet([
                        { id: 0, title: 'TITULO PRUEBA', content: 'Item 0', start: '2023-02-08T00:00:00', group: 1, editable: false, className: 'bg-primary text-white' },
                        { id: 1, content: "Item 2", start: '2023-02-08T00:00:00', group: 2 },
                        { id: 2, content: 'Item 3', start: '2023-02-09T12:00:00', group: 1, type: "point" },
                        { id: 3, content: 'Item 4', start: '2023-02-10T12:00:00', group: 1, className: 'bg-primary text-white' },
                        { id: 4, content: 'Item 5', start: '2023-02-10T03:00:00', end: '2023-02-11T14:00:00', group: 3, type: "background" },
                        { id: 5, content: 'item 6', start: '2023-02-11T14:00:00', end: '2023-02-15T14:00:00', group: 1, value: 0.72, visibleFrameTemplate: '', className: 'bg-primary text-white' },
                        { id: 6, content: 'item 7 with visibleFrameTemplate in item', group: 1, start: '2023-02-13', end: '2023-02-14', visibleFrameTemplate: '<div class="progress-wrapper"><div class="progress" style="width:80%"></div><label class="progress-label">80%<label></div>' }
                        ]);

                        loadOptions();

                       var timeline = new vis.Timeline(container,items,groups,options);
                    }
                }); //End Ajax
            }

            function loadItems(){
                items = new vis.DataSet([
                  { id: 0, title: 'TITULO PRUEBA', content: 'Item 0', start: '2023-02-08T00:00:00', group: 1, editable: false, className: 'bg-primary text-white' },
                  { id: 1, content: "Item 2", start: '2023-02-08T00:00:00', group: 2 },
                  { id: 2, content: 'Item 3', start: '2023-02-09T12:00:00', group: 1, type: "point" },
                  { id: 3, content: 'Item 4', start: '2023-02-10T12:00:00', group: 1, className: 'bg-primary text-white' },
                  { id: 4, content: 'Item 5', start: '2023-02-10T03:00:00', end: '2023-02-11T14:00:00', group: 3, type: "background" },
                  { id: 5, content: 'item 6', start: '2023-02-11T14:00:00', end: '2023-02-15T14:00:00', group: 1, value: 0.72, visibleFrameTemplate: '', className: 'bg-primary text-white' },
                  { id: 6, content: 'item 7 with visibleFrameTemplate in item', group: 1, start: '2023-02-13', end: '2023-02-14', visibleFrameTemplate: '<div class="progress-wrapper"><div class="progress" style="width:80%"></div><label class="progress-label">80%<label></div>' }
                ]);

                items.on('*', function (event, properties) {
                  logEvent(event, properties);
                  //console.log(properties.items[0]);
                });
            }

            function loadOptions(){

                //#################### Configuration for the Timeline ####################
                var min = new Date(2023, 0, 1);
                var max = new Date(2023, 5, 31);

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
                    minorLabels: { day: 'dd DD' },
                    majorLabels: { day: 'MMMM' }
                  },
                  orientation: {
                    axis: "both",
                    item: "top"
                  },
                  width: '100%',
                  min: min,                // lower limit of visible range
                  max: max,                // upper limit of visible range
                  zoomMin: 1000 * 60 * 60 * 24 * 1 * 1,             // one day in milliseconds
                  zoomMax: 1000 * 60 * 60 * 24 * 30 * 1,     // about three months in milliseconds

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
                        //insertar en base de datos.
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

            }

          </script>
        @endpush
    @endsection
@endsection

{{-- @include('partials/datetimepicker') --}}


<script src="{{asset('js/datetime/moment.min.js')}}"></script>
<script src="{{asset('js/datetime/es.js')}}"></script>
