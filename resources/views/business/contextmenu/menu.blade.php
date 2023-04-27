  <link rel="stylesheet" href="{{asset('css/contextmenu//menu.css')}}">

  <div class="context-menu row" style="display: none;">
       <!--Menu conxtual adicionado en forma dinamica por java script-->
  </div>

  @push('scripts')
    <script src="{{asset('js/contextmenu//menu.js')}}"></script>
  @endpush
