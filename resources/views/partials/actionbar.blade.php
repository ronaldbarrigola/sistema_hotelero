<div class="btn-toolbar d-flex justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
    {{-- NUEVO REGISTRO --}}

    <div class="btn-group" role="group" aria-label="First group">
        {{-- Verificando si la variable $url_voler fue enviado en la llamada, si existe se pone el boton volver, sino se ignora --}}
        @if(!empty($url_volver))
            <a href="{{url($url_volver)}}" class="btn btn-primary">VOLVER</a>
        @endif
        @if(!empty($url_nuevo))
            @if($url_nuevo=='_modal_nuevo_')
                <button class="btnMostrarModalNuevo btn btn-success">NUEVO</button>
            @else
                <a href="{{url($url_nuevo)}}" class="btn btn-success">NUEVO</a>
            @endif
        @endif
    </div>

    {{-- CHECKBOX BUSQUEDA EXACTA --}}
    <div class="btn-group float-right mt-2" role="group">
        <div class="form-check d-none">
            <input type="checkbox" class="form-check-input" id="chkBusquedaExacta" checked>
            <label class="form-check-label" for="chkBusquedaExacta">Búsq. palabra completa en columna </label>
        </div>
    </div>

    {{-- BUSQUEDA --}}
    <div class="input-group mt-1">
        <div class="input-group-prepend">
            <div class="input-group-text">{{$titulo}}</div>
        </div>
        <input id="txtBuscar" type="text" class="form-control" placeholder="Buscar"  >
        <div class="input-group-append">
            <button id="btnBuscar" class="input-group-btn btn btn-primary"><span class="icon-Lupa"></span></button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready( function () {

            // ══════════════════════ Búsqueda al presionar el boton de busqueda  ═════════════════════════════
            $('#btnBuscar').on( 'click', function () {
                 // filtrar tabla en todas las columnas de tabla
                var valor_buscado = $.trim($("#txtBuscar").val());
                    datatable_datos.search(valor_buscado).draw();

                    ContendorBotonBuscar=$(this).closest('div');
                    ContendorBotonBuscar.find('.btn_texto_filtro_tabla').remove();
                    if(valor_buscado!==""){
                        ContendorBotonBuscar.append('<button class="btn_texto_filtro_tabla btn btn-sm btn-warning"> <span class="icon-filter"></span>' + valor_buscado + '</button>');
                    }

            });

            // ══════════════════════ elimina boton y  filtro datatable ══════════════════════
                $(this).on('click', '.btn_texto_filtro_tabla', function() {
                    $(this).remove();
                    datatable_datos.search('').draw();
                });

            // ══════════════════════ Búsqueda al presionar ENTER  ════════════════════════════════════════════
            $('#txtBuscar').keypress(function(e){
            if(e.which == 13){//tecla ENTER
                $('#btnBuscar').click();//dispara el evento click del boton btnBuscar
            }
            });
            //══════════════════════════════════════════════════════════════════════════════════════════════════
        });//fin ready
    </script>
@endpush
