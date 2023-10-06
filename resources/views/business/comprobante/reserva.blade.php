<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false" id="modalViewComprobanteReserva">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 id="title_modal_view_comprobante_reserva" class="modal-title">COMPROBANTE RESERVA</h5>
                <button id='cerrarModal' type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>

            <div class="modal-body">
                <iframe id="comprobante_reserva" width="100%" height="550" src="" frameborder="0"></iframe>
            </div>

            <div class="modal-footer">

            </div>

        </div>
    </div>
</div> <!--End Modal-->

@push('scripts')
  <script>
     $(document).ready(function() {
          $('#modalViewComprobanteReserva').on('hidden.bs.modal', function() {
             $("#comprobante_reserva").attr("src","");
          })
        }); //Fin ready

        function comprobanteReserva($reserva_id){
            var archivo=$reserva_id + ".pdf";
            var path='{{asset("pdf/comprobante/reserva/doc_pdf")}}';
            path_pdf=path.replace("doc_pdf",archivo);
            $("#comprobante_reserva").attr("src",path_pdf);
            $("#modalViewComprobanteReserva").modal("show");
        }
  </script>
@endpush
