<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewDetalleCargo">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_detalle_cargo" class="modal-title">DETALLE CARGO</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                <iframe id="detalle_cargo" width="100%" height="550" src="" frameborder="0"></iframe>
            </div>

            <div class="modal-footer">

            </div>

        </div>
    </div>
</div> <!--End Modal-->

@push('scripts')
  <script>
     $(document).ready(function() {
          $('#modalViewDetalleCargo').on('hidden.bs.modal', function() {
             $("#detalle_cargo").attr("src","");
          })
        }); //Fin ready

        function comprobanteDetalleCargo($reserva_id){
            var archivo=$reserva_id + ".pdf";
            var path='{{asset("pdf/comprobante/detalle_cargo/doc_pdf")}}';
            path_pdf=path.replace("doc_pdf",archivo);
            $("#detalle_cargo").attr("src",path_pdf);
            $("#modalViewDetalleCargo").modal("show");
        }
  </script>
@endpush
