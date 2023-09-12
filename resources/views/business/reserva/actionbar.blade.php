<div class="btn-toolbar d-flex justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
    {{-- NUEVO REGISTRO --}}

    <div class="btn-group" role="group" aria-label="First group">
        <button id= "btnCreateReserva" class="btn btn-success">NUEVO</button>
    </div>

    {{-- BUSQUEDA --}}
    <div class="input-group mt-1">
        <div class="input-group-prepend">
            <div class="input-group-text">{{$titulo}}</div>
        </div>
        <input id="txtBuscarReserva" type="text" class="form-control" size="60" placeholder="Buscar"  >
        <div class="input-group-append">
            <button id="btnBuscarReserva" class="input-group-btn btn btn-primary"><span class="icon-Lupa"></span></button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready( function () {

            $('#btnBuscarReserva').on( 'click', function () {
                var valor_buscado = $.trim($("#txtBuscarReserva").val());
                datatable_reserva.search(valor_buscado).draw();
                ContendorBotonBuscar=$(this).closest('div');
                ContendorBotonBuscar.find('.btn_texto_filtro_tabla').remove();
                if(valor_buscado!==""){
                    ContendorBotonBuscar.append('<button class="btn_texto_filtro_tabla btn btn-sm btn-warning"> <span class="icon-filter"></span>' + valor_buscado + '</button>');
                }
            });

            $(this).on('click', '.btn_texto_filtro_tabla', function() {
               $(this).remove();
               datatable_reserva.search('').draw();
            });

            $('#txtBuscarReserva').keypress(function(e){
            if(e.which == 13){//tecla ENTER
               $('#btnBuscarReserva').click();//dispara el evento click del boton btnBuscar
            }
            });

            $(this).on('click', '.btn_texto_filtro_tabla', function() {
               $(this).remove();
               datatable_reserva.search('').draw();
            });

        });//fin ready
    </script>
@endpush
