@extends('layouts.plantillaFormExtendido')

@section('contenido')
    @section('panelCabecera')
       <div id="cabecera_reserva">
            @include('business/reserva/actionbar',['','titulo'=>'RESERVAS'])
       </div>
       <div id="cabecera_cargo" style="display:none">
            @include('business/transaccion/actionbar',['','titulo'=>'TRANSACCION'])
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
                    <button id= "btnPrevious" class="btn btn-secondary">Volver</button>
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

            $(document).on("click", "#btnPrevious", function(){ //El boton btnCreateCliente se encuentra en actionbar
                slideReserva();
            });
        });//fin ready

        function slideCargo(id){
            $('#cabecera_reserva').hide()
            $('#cabecera_cargo').show()
            $('#foreign_reserva_id').val(id); //La variable reserva_id_dto se encuenta en elmodulo cargo.index
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
