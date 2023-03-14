@push('scripts')
    <script src="{{asset('js/utilesjs.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".campoNumeroDecimal").validarCampoTipo('0123456789.');
            $(".campoNumeroEntero").validarCampoTipo('0123456789');
        });
    </script>
@endpush
