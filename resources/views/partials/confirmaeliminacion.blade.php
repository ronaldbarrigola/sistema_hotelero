
<div class="modal fade modal-slide-in-right" aria-hidden="true" data-backdrop="static" data-keyboard="false"  tabindex="-1"
role="dialog" id="modaleliminar">

   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
           <h5 class="modal-title">ELIMINAR</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">X</span>
               </button>
           </div>

           <div class="modal-body">
               <p>¿desea eliminar el registro con id <span id="parametro" style="color:red;"></span> ?</p>
           </div>

           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
               <a href="#" class="btn btn-primary eliminarfila" data-dismiss="modal">Confirmar</a>
           </div>

       </div>
   </div>
</div>

<!-- TODO LO QUE ESTA ENTRE push('scripts') Y endpush se ubicara justo en la plantilla  .blade.php en la seccion stack('scripts') -->
@push('scripts')
<script>
   $(window).ready(function() {
       var botonEliminar;//variable global para almacenar el boton en el que se hizo clic para eliminar
       //==================================================================================================================
       //  al presionar el el boton eliminar se muestra el formulario modal con bootstrap, pero se necesita capturar el boton
       //  en la que se hizo clic, para que luego al confirmar la eliminacion(boton del modal) se elimine la fila del boton presionado en el grid.
       //==================================================================================================================

       // la diferencia etre .click y .on es que .on incluso puede asignar el evento aunque el elemento se cree despues en el DOM... esto es util para ajax, porque cargar elementos luego de cargar el dom
       $('table').on( "click",'.preguntaeliminar', function() {
           botonEliminar=this;//capturando el boton en el que se hizo click para eliminar.
           $("#parametro").html($(botonEliminar).data('idmodelo'));
       });

       //==================================================================================================================
       // eliminando fila mediante ajax invocando al la url para eliminar
       //==================================================================================================================
       if ($('.eliminarfila').length) {
           $('.eliminarfila').on( "click", function(e) {
                var id = $(botonEliminar).data('idmodelo');
                //if(!confirm("¿esta seguro de eliminar el registro con id:"+id+" ?","ELIMINAR REGISTRO")) return false;
                var row =  $(botonEliminar).parents('tr');
                url_delete={!! json_encode($url_base_eliminar) !!};//leyendo parametro recibido de laravel
                url_delete=URL_BASE+"/"+url_delete+"/"+id;
                //url_delete=url_delete.replace('_REMPLAZAR_ID', id);// remplazando el id
                //console.log('{{ csrf_token() }}');
                var tiempoEfecto=500;
                row.fadeOut(tiempoEfecto);//ocultando fila
                $.ajax({
                    type: "POST",
                    url: url_delete,
                    data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},//data:{'_method':'DELETE','_token': '{{ csrf_token() }}'},
                    dataType: 'json',
                    success: function(result){
                        setTimeout(() => {
                            row.remove();//eliminando fila
                        }, tiempoEfecto);
                    },
                    error:function(resultado){
                        row.fadeIn(tiempoEfecto);//volviendo a mostrar fila
                        //row.show();//volviendo a mostrar fila
                    }
                });

            });//fin click eliminarfila
       }//fin if eliminarfila

   });
</script>
@endpush
