@extends('layouts.plantillaFormExtendido')

@section('contenido')
    @section('panelCabecera')
       <div class="cabecera_principal">
            @include('business/reserva/actionbar',['','titulo'=>'RESERVAS'])
       </div>
       <div class="cabecera_transaccion" style="display:none">
            @include('business/transaccion/actionbar',['','titulo'=>'CARGOS'])
       </div>
    @endsection
    @section('panelCuerpo')
        <div class="carouselReserva carousel slide" data-ride="carousel" data-interval="false">
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

    </script>
@endpush
