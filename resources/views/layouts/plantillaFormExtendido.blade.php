@extends('layouts.plantilla')
@section('content_index')
    @yield('contenido')
    <div class="  ">
        <div class="card-header paddingminimo ">
            @yield('panelCabecera')
        </div>
        <div class="card-body paddingminimo ">
            @yield('panelCuerpo')
        </div>

        <!-- @yield('panelTabla') -->
        <!-- <div class="card-footer paddingminimo">
            @yield('panelPie')
        </div> -->
    </div>
@endsection

@push('scripts')
  <script>
     $("#main_content_index").show(); //Muesta plantilla para que el index ocupe toda la pantalla
  </script>
@endpush
