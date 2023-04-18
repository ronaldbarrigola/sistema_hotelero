  <link rel="stylesheet" href="{{asset('css/contextmenu//menu.css')}}">

  <div class="context-menu" style="display: none;">
    <ul>
      <li><a href="#">Opción 1</a></li>
      <li><a href="#">Opción 2</a></li>
      <li><a href="#">Opción 3</a></li>
    </ul>
  </div>

  @push('scripts')
    <script src="{{asset('js/contextmenu//menu.js')}}"></script>
  @endpush
