        // ==============================================================================================
        // PROGRAMANDO BUSQUEDA POR COLUMNAS EN CABECERA
        //==============================================================================================
        function busquedaPorColumna(selectorTabla, dataTablesObjeto, ArrayColsOmitidas) {
            $(selectorTabla + ' thead tr').clone(true).appendTo(selectorTabla + ' thead'); // clonando cabecera para botones filtro
            $("#chkBusquedaExacta").closest("div").removeClass("d-none");
            $(selectorTabla + ' thead tr:eq(1) th').each(function(i) {
                if (ArrayColsOmitidas.includes(i)) {
                    //evitando mostrar boton filtro
                    $(this).html('');
                    return;
                }
                // $(this).html('<div class="btn-group"> <button class="icon-Lupa btn btn-sm btn-primary "></button><button class="busqueda_palabra icon-Lupa btn btn-sm btn-success "></button></div>'); // creando boton busqueda lupa
                $(this).html('<button class="icon-Lupa btn btn-sm btn-primary "></button>'); // creando boton busqueda lupa

                //-------------- funcion click para boton con clase icon-lupa ---
                $(this).on('click', '.icon-Lupa', function() {
                    // filtrar columna correspondiente al boton sobre la que se hizo click
                    var valor_buscado = $("#txtBuscar").val().trim();
                    dataTablesObjeto.column(i).search(valor_buscado).draw();


                    // //if (dataTablesObjeto.column(i).search() !== valor_buscado) {
                    // if ($("#chkBusquedaExacta")[0].checked) {
                    //     // search(texto,regexp,smart,case-insensitive)
                    //     dataTablesObjeto.column(i).search('\\b' + valor_buscado + '\\b', true, false).draw();

                    //     // var arr = valor_buscado.split(';');
                    //     // var pattern = ("\\b" + arr.join('\\b|\\b') + '\\b');
                    //     //dataTablesObjeto.column(i).search(pattern, true, false).draw();

                    // } else {
                    //     var valor_buscado_array = valor_buscado.split(';');
                    //     dataTablesObjeto.column(i).search(valor_buscado_array).draw();
                    // }


                    celdaBusqueda = $(this).closest('th');
                    celdaBusqueda.find('.btn_texto_filtro').remove();
                    if ($.trim(valor_buscado) !== "") {
                        celdaBusqueda.append('<button class="btn_texto_filtro btn btn-sm btn-warning"> <span class="icon-filter"></span>' + valor_buscado + '</button>');
                    }
                    //}
                });

                //--------------  funcion click para boton con clase bnt_texto_filtro  ---
                $(this).on('click', '.btn_texto_filtro', function() {
                    // elimina boton y elimina filtro para columna correspondiente al boton sobre la que se hizo click
                    $(this).remove();
                    datatable_datos.column(i).search('').draw();
                });

            });
        }
        //==============FIN  PROGRAMANDO BUSQUEDA POR COLUMNAS EN CABECERA