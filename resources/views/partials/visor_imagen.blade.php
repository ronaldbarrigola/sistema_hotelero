<div class="modal fade modal-slide-in-right" aria-hidden="true"
     role="dialog" tabindex="-1" id="modalVisorImagen">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">IMAGEN</h5>
                    <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>

                <div class="modal-body">
                    <img id="imagen" src="" alt="imagen" width='100%' height='100%' class='img-thumbnail'>
                </div>

                <div class="modal-footer">
                </div>

            </div>
        </div>
</div>

<!-- TODO LO QUE ESTA ENTRE push('scripts') Y endpush se ubicara justo en la plantilla  .blade.php en la seccion stack('scripts') -->
@push('scripts')
    <script>
        $(window).ready(function() {
            var imgImagen;//variable global para almacenar el boton en el que se hizo clic para eliminar
            //==================================================================================================================
            //  al presionar el el boton eliminar se muestra el formulario modal con bootstrap, pero se necesita capturar el boton
            //  en la que se hizo clic, para que luego al confirmar la eliminacion(boton del modal) se elimine la fila del boton presionado en el grid.
            //==================================================================================================================

            //$('.ventanaImagen').click(function(){
             $(document).on("click", ".img-thumbnail", function(){
            //$('.img-thumbnail').click(function(){// mejor usar on ya que clic falla cuando se carga por AJAX
                $('#imagen').attr('src','');
                imgImagen=this;//capturando la imagen en la que se hizo click para eliminar.
                path_imagen_original=$(imgImagen).attr('src').replace('/miniaturas','');
                //alert(path_imagen_original);
                $('#imagen').attr('src',path_imagen_original);
                $('#modalVisorImagen').modal('show');
            });
            //==================================================================================================================

            // $("#cerrarModal").click(function(){
            //     $('#modalVisorImagen').modal('hide');
            // });
        }); //fin ready


    </script>
@endpush

