//Para escribir solo letras  $('#miCampo1').validarCampoTipo(' abcdefghijklmnñopqrstuvwxyzáéiou');
//Para escribir solo numeros  $('#miCampo2').validarCampoTipo('0123456789');
//nota.- esto no funciona para android, porque android no tiene un teclado fisico y no devuelve bien el codascci del teclado.
(function(a) {
    a.fn.validarCampoTipo = function(b) {
        a(this).on({
            keypress: function(a) {
                var c = a.which,
                    d = a.keyCode,
                    e = String.fromCharCode(c).toLowerCase(),
                    f = b;
                (-1 != f.indexOf(e) || 9 == d || 37 != c && 37 == d || 39 == d && 39 != c || 8 == d || 46 == d && 46 != c) && 161 != c || a.preventDefault()
            }
        })
    }
})(jQuery);

function fechaActual() {
    var fecha = new Date(); //Fecha actual
    var mes = fecha.getMonth() + 1; //obteniendo mes
    var dia = fecha.getDate(); //obteniendo dia
    var ano = fecha.getFullYear(); //obteniendo año
    if (dia < 10)
        dia = '0' + dia; //agrega cero si el menor de 10
    if (mes < 10)
        mes = '0' + mes //agrega cero si el menor de 10
    return ano + "-" + mes + "-" + dia;
}

function messageAlert(mensaje) {
    boot4.alert({
        msg: mensaje,
        title: "Validacion",
        callback: function() {
            //Acciones
        }
    }, "Aceptar");
}


//funcion valida numeros

// $('.campoNumeroDecimal').keyup(function (){
//     this.value = (this.value + '').replace(/[^0-9.]/g, '');
// });

// $('.campoNumeroEntero').keyup(function (){
//     this.value = (this.value + '').replace(/[^0-9]/g, '');
// });



//=====================================================================================================================
//vista previa de imagen a subir.
//=====================================================================================================================
function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var img_destino = $(input).data("img-preview");

        reader.onload = function(e) {
            $(img_destino).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

//=====================================================================================================================
//verifica que el tamaño del archivo seleccionado en un input, tipo file,
//tenga como maximo, el tamaño indicado en el atributo size del input en KiloBytes
//el mensaje de advertencia se muestra en un div cuyo id =(name_input)-msg-error
//=====================================================================================================================
function validarTamanioArchivo(inputFile) {
    $("#" + inputFile.name + "-msg-error").html(""); //limpiando mensajes anteriores
    var tamLimite_KB = $(inputFile).attr('size');
    var tamArchivo_Byte = $(inputFile)[0].files[0].size; // tamaño en bytes
    var tamArchivo_KB = parseInt(tamArchivo_Byte / 1024); //tamaño en kiloBytes
    var tamArchivo_MB = (tamArchivo_KB / 1024).toFixed(2); //tamaño en MegaBytes
    if (tamArchivo_KB > tamLimite_KB) { //comparando con tamaño limite
        msg = '<p  class="alert alert-danger"> el archivo pesa ' + tamArchivo_MB + 'MB, y el limite a subir es de: ' + (tamLimite_KB / 1024) + 'MB</p>';
    } else {
        msg = tamArchivo_KB < 1024 ? tamArchivo_KB + "KB" : msg = tamArchivo_MB + "MB";
    }
    $("#" + inputFile.name + "-msg-error").html(msg);
}

//=====================================================================================================================
// funcion para mostrar una vistra previa de la imagen a subir y verificacion del tamaño limite a subir.
//================================================================================================================
function previewValidaTamanio(inputFile) {
    filePreview(inputFile);
    validarTamanioArchivo(inputFile);
}

//=====================================================================================================================
// dando propiedad draggable  a modal dialog de bootstrap.
//================================================================================================================
$('body').on('mousedown', '.draggable', function(e1) {
    var xIni = e1.pageX;
    var yIni = e1.pageY;
    var posIni = $(this).parent().parent().offset();
    $(this).parent().parent().addClass('arrastrable').parents().on('mousemove', function(e2) {
        var x = e2.pageY - yIni;
        var y = e2.pageX - xIni;
        //$(".popup-titulo").html("xini:"+xIni+"\yini:"+yIni+"\nx:"+x+"\ny:"+y);
        $('.arrastrable').offset({
            top: posIni.top + x,
            left: posIni.left + y
                //top: e.pageY - $('.arrastrable').outerHeight() / 2,
                //left: e.pageX - $('.arrastrable').outerWidth() / 2
        }).on('mouseup', function() {
            $(this).removeClass('arrastrable');
        });
    });
    e1.preventDefault();
}).on('mouseup', function() {
    $('.arrastrable').removeClass('arrastrable');
});
//================================================================================================================
// mostrar spinner de ocupado, o procesando y deshabilitando botones e inputs de formulario o modal o cualquier contenedor especificado
//================================================================================================================
function activarEstadoProcesando(selectorContenedor, selectorBoton) {
    boton = $(selectorBoton);
    deshabilitarControlesContenedor(selectorContenedor);
    boton.val(boton.html()); //salvando valor que aparece en el boton en la propiedad value del boton,  antes de poner el texto animado
    boton.html("<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>" + boton.data('textoprocesando'));
}

function desactivarEstadoProcesando(selectorContenedor, selectorBoton) {
    boton = $(selectorBoton);
    habilitarControlesContenedor(selectorContenedor);
    boton.html(boton.val());
}


function deshabilitarControlesContenedor(selectorContenedor) {
    $(selectorContenedor).find('input, textarea, button, select').attr('disabled', 'disabled'); //desactivando elementos
}

function habilitarControlesContenedor(selectorContenedor) {
    $(selectorContenedor).find('input, textarea, button, select').removeAttr('disabled'); //desactivando elementos
}

//Para evitar submit al momento de presionar la tecla ENTER
$(document).on('keypress', 'input', function(e) {
    if (e.keyCode == 13 && e.target.type !== 'submit') {
        e.preventDefault();
        //return $(e.target).blur().focus();
        return $(e.target).blur();
    }
});

//================================================================================================================