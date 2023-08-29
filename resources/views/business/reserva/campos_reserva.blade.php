    <input type="hidden" name="reserva_id" id="reserva_id">
    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>RESERVA</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="form-group">
                        <label for="cliente_id" class="my-0" ><strong>Reservado por:</strong></label>
                        <div class="input-group-append">
                            <select name="cliente_id" id="cliente_id" required class="form-control selectpicker border" data-live-search="true" >
                               <!--Se carga los datos por ajax-->
                            </select>
                            <button type="button" id="btnModalCreateCliente" class="input-group-btn btn btn-light"><span class="icon-plus"></span></button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="habitacion_id" class="my-0" ><strong>Habitacion:</strong></label>
                        <select name="habitacion_id" id="habitacion_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="reserva_precio_unidad_ref" class="my-0"><strong>Precio Unidad Habitacion:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Bs.</strong></span>
                            <input type="number" name="reserva_precio_unidad_ref" id="reserva_precio_unidad_ref" readonly class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="paquete_id" class="my-0" ><strong>Paquete:</strong></label>
                        <select name="paquete_id" id="paquete_id"  class="form-control selectpicker border" data-live-search="true" >
                             <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="num_adulto" class="my-0"><strong>Numeros de adultos:</strong></label>
                        <input type="number" name="num_adulto" id="num_adulto" max="99" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="num_nino" class="my-0"><strong>Numero de Niños:</strong></label>
                        <input type="number" name="num_nino" id="num_nino" max="99" class="form-control" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="procedencia_pais_id" class="my-0" ><strong>Pais Procedencia:</strong></label>
                        <select name="procedencia_pais_id" id="procedencia_pais_id" required class="form-control selectpicker border" data-live-search="true" >
                             <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="procedencia_ciudad_id" class="my-0" ><strong>Ciudad Procedencia:</strong></label>
                        <select name="procedencia_ciudad_id" id="procedencia_ciudad_id" required class="form-control selectpicker border" data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

           </div>
        </div>
    </div>

    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>SERVICIO</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">
                 <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="servicio_id" class="my-0" ><strong>Servicio:</strong></label>
                        <select name="servicio_id" id="servicio_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="motivo_id" class="my-0" ><strong>Motivo:</strong></label>
                        <select name="motivo_id" id="motivo_id"  class="form-control selectpicker border" required data-live-search="true" >
                            <!--Se carga los datos por ajax-->
                        </select>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="fecha_ini"  class="my-0"><strong>Fecha Ingreso:</strong></label>
                        <input type="date" id="fecha_ini" name="fecha_ini" required class="form-control">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="hora_ini"  class="my-0"><strong>Hora Ingreso:</strong></label>
                        <input type="time" id="hora_ini" name="hora_ini" required class="form-control">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="fecha_fin"  class="my-0"><strong>Fecha Salida:</strong></label>
                        <input type="date" id="fecha_fin" name="fecha_fin" required class="form-control">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="hora_fin"  class="my-0"><strong>Hora Salida:</strong></label>
                        <input type="time" id="hora_fin" name="hora_fin" required class="form-control">
                    </div>
                </div>
           </div>
        </div>
    </div>

    <div class="panel_ordenante card">
        <div class="card-header py-0">
            <strong>CARGO</strong>
        </div>
        <div class="card-body py-0">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="reserva_cantidad" class="my-0"><strong>Cantidad:</strong></label>
                        <input type="number" name="reserva_cantidad" id="reserva_cantidad" min="1" required class="form-control"  onkeydown="event.preventDefault()" style="background-color: #f6f6f6;" placeholder="0">
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="reserva_precio_unidad" class="my-0"><strong>Precio Unidad:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Bs.</strong></span>
                            <input type="number" name="reserva_precio_unidad" id="reserva_precio_unidad" min="1" required readonly class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="form-group">
                        <label for="reserva_descuento_porcentaje" class="my-0"><strong>Descuento:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>%</strong></span>
                            <input type="number" name="reserva_descuento_porcentaje" id="reserva_descuento_porcentaje" min="0"  max="100" step="0.01"  class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="form-group">
                        <label for="reserva_descuento" class="my-0"><strong>Descuento:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Bs.</strong></span>
                            <input type="number" name="reserva_descuento"  id="reserva_descuento" min="0" step="0.01" class="form-control" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="form-group">
                        <label for="reserva_monto" class="my-0"><strong>Total Cargo:</strong></label>
                        <div class="input-group-prepend">
                            <span class="input-group-text"><strong>Bs.</strong></span>
                            <input type="number" name="reserva_monto" id="reserva_monto" min="1" step="0.01" required class="form-control" onkeydown="event.preventDefault()" style="background-color: #f6f6f6;" placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="detalle" class="my-0"><strong>Detalle:</strong></label>
                <input type="text" name="detalle" id="detalle" class="form-control">
            </div>
        </div>
    </div>

    @push('scripts')
    <script>

          $(document).ready(function() {
              $(document).on("keyup", "#reserva_cantidad", function(){
                  reservaCalcularCargo();
              });

              $(document).on("keyup", "#reserva_descuento_porcentaje", function(){
                  reservaDescuentoPorcentaje();
              });

              $(document).on("keyup", "#reserva_descuento", function(){
                  reservaDescuento();
              });

              $(document).on("keyup", "#reserva_precio_unidad", function(){
                  reservaCalcularCargo();
              });

          }); //Fin ready

          function reservaDescuentoPorcentaje(){
              var cantidad=$("#reserva_cantidad").val();
              var precio_unidad=$("#reserva_precio_unidad").val();
              var total_cargo=0;
              var porcentaje=$("#reserva_descuento_porcentaje").val();
              var descuento=0;

              cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
              precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
              total_cargo=cantidad*precio_unidad;
              porcentaje=(porcentaje!=null&&porcentaje!=""&&porcentaje>0)?porcentaje:0;
              if(porcentaje>0&&porcentaje<=100){
                  //descuento=Math.round(parseFloat((total_cargo*porcentaje)/100));
                  descuento=Math.round(parseFloat((total_cargo*porcentaje)/100)*100.0)/100.0;
              } else if(porcentaje>100) {
                  $("#reserva_descuento_porcentaje").val(100);
                  descuento=total_cargo;
              } else {
                  $("#reserva_descuento_porcentaje").val("");
              }
              $("#reserva_descuento").val(descuento);
              reservaCalcularCargo();
          }

          function reservaDescuento(){
              var cantidad=$("#reserva_cantidad").val();
              var precio_unidad=$("#reserva_precio_unidad").val();
              var total_cargo=0;
              var descuento=$("#reserva_descuento").val();
              var porcentaje=0;

              cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
              precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
              total_cargo=cantidad*precio_unidad;
              descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
              if(descuento>0&&descuento<=total_cargo){
                  //porcentaje=Math.round(parseFloat((descuento/total_cargo)*100));
                  porcentaje=Math.round(parseFloat((descuento/total_cargo)*100)*100.0)/100.0;
              } else if(descuento>total_cargo) {
                  $("#reserva_descuento").val(total_cargo);
                  porcentaje=100;
              } else {
                  $("#reserva_descuento").val("");
              }
              $("#reserva_descuento_porcentaje").val(porcentaje);
              reservaCalcularCargo();
          }

          function cantidadReserva(){
            var fecha_ini=$("#fecha_ini").val();
            var fecha_fin=$("#fecha_fin").val();
            var timeStart = new Date(fecha_ini);
            var timeEnd = new Date(fecha_fin);
            var dias=0;
            if (timeEnd >= timeStart)
            {
                var diff = timeEnd.getTime() - timeStart.getTime();
                dias= Math.round(diff / (1000 * 60 * 60 * 24));
                dias=(dias!=0)?dias:1; //Validar cuando sean la misma fecha
            }
            else if (timeEnd != null && timeEnd < timeStart) {
                messageAlert("La fecha ingreso debe ser mayor a la fecha de salida");
            }
            $("#reserva_cantidad").val(dias);
        }

        function precioUnidadReserva(){
            var servicio_id=$("#servicio_id").val();
            if(servicio_id==2){ //1: HOSPEDAJE 2:DAY USE
                $("#reserva_precio_unidad").removeAttr('readonly');
            } else {
                var precio=$('#habitacion_id option:selected').data("precio");
                var precio_unidad=(precio!=null&&precio!=""&&precio>0)?precio:0;
                $("#reserva_precio_unidad").val(precio_unidad);
                $("#reserva_precio_unidad").attr('readonly','readonly');
            }
        }

        function reservaCalcularCargo(){
            cantidadReserva()
            precioUnidadReserva();
            var cargo=0;
            var cantidad=$("#reserva_cantidad").val();
            var precio_unidad=$("#reserva_precio_unidad").val();
            var descuento=$("#reserva_descuento").val();
            //validacion de datos
            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
            descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
            cargo= cantidad*precio_unidad-descuento;
            $("#reserva_monto").val(cargo.toFixed(2));
        }

    </script>
  @endpush

  @include('partials/utilesjs')





