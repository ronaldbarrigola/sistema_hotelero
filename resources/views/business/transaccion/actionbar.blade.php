<div class="btn-toolbar d-flex justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
    {{-- NUEVO REGISTRO --}}

    <div class="btn-group" role="group" aria-label="First group">
        <button id= "btnCreateTransaccion" class="btn btn-success">NUEVO</button>
        <button id= "btnRetornarReserva" class="btn btn-primary" onclick="slideReserva()">Volver</button>   {{-- slideReserva()  se encuentra en el modulo transaccion.crete_edit --}}
    </div>

    <div class="form-group mt-1" role="group">
        <span>CLIENTE : </span><strong id="nombre_cliente"></strong>
        <span>NRO. HABITACION : </span><strong id="nro_habitacion"></strong>
    </div>

    {{-- BUSQUEDA --}}
    <div class="input-group mt-1">
        <div class="input-group-prepend">
            <div class="input-group-text">{{$titulo}}</div>
        </div>
        <input id="txtBuscarTransaccion" type="text" class="form-control" size="60" placeholder="Buscar"  >
        <div class="input-group-append">
            <button id="btnBuscarTransaccion" class="input-group-btn btn btn-primary"><span class="icon-Lupa"></span></button>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        $(document).ready( function () {

            $('#btnBuscarTransaccion').on( 'click', function () {
                var valor_buscado = $.trim($("#txtBuscarTransaccion").val());
                datatable_transaccion.search(valor_buscado).draw();
                ContendorBotonBuscar=$(this).closest('div');
                ContendorBotonBuscar.find('.btn_texto_filtro_tabla').remove();
                if(valor_buscado!==""){
                    ContendorBotonBuscar.append('<button class="btn_texto_filtro_tabla btn btn-sm btn-warning"> <span class="icon-filter"></span>' + valor_buscado + '</button>');
                }
            });

            $(this).on('click', '.btn_texto_filtro_tabla', function() {
               $(this).remove();
               datatable_transaccion.search('').draw();
            });

            $('#txtBuscarTransaccion').keypress(function(e){
            if(e.which == 13){//tecla ENTER
               $('#btnBuscarTransaccion').click();//dispara el evento click del boton btnBuscar
            }
            });

            $(this).on('click', '.btn_texto_filtro_tabla', function() {
               $(this).remove();
               datatable_transaccion.search('').draw();
            });

        });//fin ready
    </script>
@endpush
