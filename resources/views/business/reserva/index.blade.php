@extends('layouts.plantillaFormExtendido')

@section('contenido')
    @section('panelCabecera')
       <div id="cabecera_reserva">
            @include('business/reserva/actionbar',['','titulo'=>'RESERVAS'])
       </div>
       <div id="cabecera_cargo" style="display:none">
            @include('business/transaccion/actionbar',['','titulo'=>'CARGOS'])
       </div>
    @endsection
    @section('panelCuerpo')
        <div id="carouselReserva" class="carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    @include('business/reserva/datatable_reserva')
                </div>
                <div class="carousel-item">
                    @include('business/transaccion/datatable_transaccion')
                </div>
            </div>
        </div>
    @endsection
@endsection

@push('scripts')
    <script>

        $(document).ready( function () {

            $(document).on("click", "#btnCreateReserva", function(){ //El boton btnCreateCliente se encuentra en actionbar
                createReserva();
            });

        });//fin ready

        function slideCargo($this){
            fila=$($this).closest("tr");
            id=$this.id;
            var cliente = fila.find("td:eq(2)").text();//Tabla reserva
            var nro_habitacion = fila.find("td:eq(3)").text();//Tabla reserva
            $('#cabecera_reserva').hide()
            $('#cabecera_cargo').show()
            $('#nombre_cliente').text(cliente.toUpperCase()); //El campo nombre_cliente se encuenta en el modulo transaccion.actionbar
            $('#nro_habitacion').text(nro_habitacion); //El campo nro_reserva se encuenta en el modulo transaccion.actionbar
            $('#foreign_reserva_id').val(id); //El campo foreign_reserva_id se encuenta en el modulo cargo.index
            datatable_transaccion.ajax.reload();
            $('#carouselReserva').carousel('next');
        }

        function slideReserva(){
            $('#cabecera_cargo').hide()
            $('#cabecera_reserva').show()
            $('#carouselReserva').carousel('prev');
        }


    </script>
@endpush
