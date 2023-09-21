<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="cantidad" class="my-0"><strong>Cantidad:</strong></label>
            <input type="number" name="cantidad" id="cantidad" min="1" class="form-control" placeholder="0">
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="precio_unidad" class="my-0"><strong>Precio Unidad:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>Bs.</strong></span>
                <input type="number" name="precio_unidad" id="precio_unidad" min="1" readonly class="form-control" placeholder="0">
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="descuento_porcentaje" class="my-0"><strong>Descuento:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>%</strong></span>
                <input type="number" name="descuento_porcentaje" min="0"  max="100" step="0.01" id="descuento_porcentaje" class="form-control" placeholder="0">
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-3 col-12">
        <div class="form-group">
            <label for="descuento" class="my-0"><strong>Descuento:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>Bs.</strong></span>
                <input type="number" name="descuento"  id="descuento" min="0" step="0.01" class="form-control" placeholder="0">
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
        <div class="form-group">
            <label for="monto" class="my-0"><strong>Total Cargo:</strong></label>
            <div class="input-group-prepend">
                <span class="input-group-text"><strong>Bs.</strong></span>
                <input type="number" name="monto" id="monto" min="1" step="0.01" class="form-control" onkeydown="event.preventDefault()" style="background-color: #f6f6f6;" placeholder="0">
            </div>
        </div>
    </div>
</div>

@push('scripts')
  <script>

        $(document).ready(function() {
            $(document).on("keyup", "#cantidad", function(){
                calcularCargo();
            });

            $(document).on("keyup", "#descuento_porcentaje", function(){
                descuentoPorcentaje();
            });

            $(document).on("keyup", "#descuento", function(){
                descuento();
            });

            $(document).on("keyup", "#precio_unidad", function(){
                calcularCargo();
            });

        }); //Fin ready

        function descuentoPorcentaje(){
            var cantidad=$("#cantidad").val();
            var precio_unidad=$("#precio_unidad").val();
            var total_cargo=0;
            var porcentaje=$("#descuento_porcentaje").val();
            var descuento=0;

            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
            total_cargo=cantidad*precio_unidad;
            porcentaje=(porcentaje!=null&&porcentaje!=""&&porcentaje>0)?porcentaje:0;
            if(porcentaje>0&&porcentaje<=100){
                //descuento=Math.round(parseFloat((total_cargo*porcentaje)/100));
                descuento=Math.round(parseFloat((total_cargo*porcentaje)/100)*100.0)/100.0;
            } else if(porcentaje>100) {
                $("#descuento_porcentaje").val(100);
                descuento=total_cargo;
            } else {
                $("#descuento_porcentaje").val("");
            }
            $("#descuento").val(descuento);
            calcularCargo();

        }

        function descuento(){
            var cantidad=$("#cantidad").val();
            var precio_unidad=$("#precio_unidad").val();
            var total_cargo=0;
            var descuento=$("#descuento").val();
            var porcentaje=0;

            cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
            precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
            total_cargo=cantidad*precio_unidad;
            descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
            if(descuento>0&&descuento<=total_cargo){
                //porcentaje=Math.round(parseFloat((descuento/total_cargo)*100));
                porcentaje=Math.round(parseFloat((descuento/total_cargo)*100)*100.0)/100.0;
            } else if(descuento>total_cargo) {
                $("#descuento").val(total_cargo);
                porcentaje=100;
            } else {
                $("#descuento").val("");
            }
            $("#descuento_porcentaje").val(porcentaje);
            calcularCargo();
        }

        function calcularCargo(){
             cantidadReserva()
             precioUnidadReserva();
             var cargo=0;
             var cantidad=$("#cantidad").val();
             var precio_unidad=$("#precio_unidad").val();
             var descuento=$("#descuento").val();
             //validacion de datos
             cantidad=(cantidad!=null&&cantidad!=""&&cantidad>0)?cantidad:0;
             precio_unidad=(precio_unidad!=null&&precio_unidad!=""&&precio_unidad>0)?precio_unidad:0;
             descuento=(descuento!=null&&descuento!=""&&descuento>0)?descuento:0;
             cargo= cantidad*precio_unidad-descuento;
             $("#monto").val(cargo.toFixed(2));
        }

  </script>
@endpush

@include('partials/utilesjs')
