@extends('layouts.plantillaFormExtendido')

@section('contenido')
    @section('panelCabecera')
       <div class="cabecera_principal">
            @include('business/reserva/actionbar',['','titulo'=>'RESERVAS'])
       </div>
       <div class="cabecera_transaccion" style="display:none">
            @include('business/transaccion/actionbar',['','titulo'=>'CARGOS'])
       </div>
       <div class="cabecera_huesped" style="display:none">
            @include('business/huesped/actionbar',['','titulo'=>'HUESPED'])
       </div>
    @endsection
    @section('panelCuerpo')
        <div class="carouselReserva carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active" id="slide-1">
                    @include('business/reserva/datatable_reserva')
                </div>
                <div class="carousel-item" id="slide-2">
                    @include('business/transaccion/datatable_transaccion')
                </div>
                <div class="carousel-item" id="slide-3">
                    @include('business/huesped/datatable_huesped')
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

    </script>
@endpush
