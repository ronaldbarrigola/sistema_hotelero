@push('estilos')
    <link href="{{ asset('css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">               
@endpush

@push('scripts')
    <script src="{{asset('js/datetime/moment.min.js')}}"></script>
    <script src="{{asset('js/datetime/es.js')}}"></script>
    <script src="{{asset('js/datetime/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script type="text/javascript">
   
        $(document).ready(function() {
            $('.datetimepicker_calendario').datetimepicker({
                locale:'es',
                format:'DD/MM/YYYY'
            });
        });
    </script>
@endpush