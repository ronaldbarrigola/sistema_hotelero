<div class="btn-toolbar d-flex justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
    {{-- NUEVO REGISTRO --}}

    <div class="btn-group" role="group" aria-label="First group">
        <button id= "btnCreateHuesped" class="btn btn-success">NUEVO</button>
        <button class="btn btn-primary" onclick="slideReserva()">Volver</button>   {{-- slideReserva()  se encuentra en el modulo reserva.crete_edit --}}
    </div>

    <div class="form-group mt-1" role="group">
        <span>CLIENTE : </span><strong id="huesped_nombre_cliente"></strong>
        <span>NRO. HABITACION : </span><strong id="huesped_nro_habitacion"></strong>
    </div>

    {{-- BUSQUEDA --}}
    <div class="input-group mt-1">
        <div class="input-group-prepend">
            <div class="input-group-text">{{$titulo}}</div>
        </div>
        <input id="txtBuscar" type="text" class="form-control" size="60" placeholder="Buscar"  >
        <div class="input-group-append">
            <button id="btnBuscar" class="input-group-btn btn btn-primary"><span class="icon-Lupa"></span></button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready( function () {

            $('#btnBuscar').on( 'click', function () {
                var valor_buscado = $.trim($("#txtBuscar").val());
                datatable_huesped.search(valor_buscado).draw();
                ContendorBotonBuscar=$(this).closest('div');
                ContendorBotonBuscar.find('.btn_texto_filtro_tabla').remove();
                if(valor_buscado!==""){
                    ContendorBotonBuscar.append('<button class="btn_texto_filtro_tabla btn btn-sm btn-warning"> <span class="icon-filter"></span>' + valor_buscado + '</button>');
                }
            });

            $(this).on('click', '.btn_texto_filtro_tabla', function() {
               $(this).remove();
               datatable_huesped.search('').draw();
            });

            $('#txtBuscar').keypress(function(e){
            if(e.which == 13){//tecla ENTER
               $('#btnBuscar').click();//dispara el evento click del boton btnBuscar
            }
            });

            $(this).on('click', '.btn_texto_filtro_tabla', function() {
               $(this).remove();
               datatable_huesped.search('').draw();
            });

        });//fin ready
    </script>
@endpush
